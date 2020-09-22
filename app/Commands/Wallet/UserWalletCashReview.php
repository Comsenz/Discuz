<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Commands\Wallet;

use App\Events\Wallet\Cash;
use App\Exceptions\WalletException;
use App\Models\User;
use App\Models\UserWalletCash;
use App\Models\UserWalletLog;
use App\Models\UserWallet;
use App\Trade\Config\GatewayConfig;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use Discuz\Auth\AssertPermissionTrait;

class UserWalletCashReview
{
    use AssertPermissionTrait;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 请求的数据.
     *
     * @var array
     */
    public $data;

    /**
     * 请求ip地址
     * @var string
     */
    public $ip_address;
    /**
     * @var ConnectionInterface
     */
    public $connection;
    /**
     * @var Dispatcher
     */
    public $events;

    /**
     * 初始化命令参数
     * @param User $actor 执行操作的用户.
     * @param Collection $data 请求的数据.
     * @param $ip_address
     */
    public function __construct(User $actor, Collection $data, $ip_address)
    {
        $this->actor      = $actor;
        $this->data       = $data;
        $this->ip_address = $ip_address;
    }

    /**
     * @param Validator $validator
     * @param Dispatcher $events
     * @param ConnectionInterface $connection
     * @return array 审核结果
     * @throws WalletException
     * @throws ValidationException
     */
    public function handle(Validator $validator, Dispatcher $events, ConnectionInterface $connection)
    {
        $this->events = $events;
        $this->connection = $connection;
        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'cash.review');
        // 验证参数
        $validator_info = $validator->make($this->data->toArray(), [
            'ids'         => 'required|array',
            'cash_status' => 'required|int',
            'remark'      => 'sometimes|string|max:255',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        $ids         = (array) Arr::get($this->data, 'ids');
        $cash_status = (int) Arr::get($this->data, 'cash_status');

        //只允许修改为审核通过或审核不通过
        if (!in_array($cash_status, [UserWalletCash::STATUS_REVIEWED, UserWalletCash::STATUS_REVIEW_FAILED, UserWalletCash::STATUS_PAID])) {
            throw new WalletException('operate_forbidden');
        }

        $status_result = []; //结果数组
        $collection    = collect($ids)
            ->unique()
            ->map(function ($id) use ($cash_status, &$status_result) {
                //取出待审核数据
                $cash_record = UserWalletCash::find($id);
                //只允许修改未审核的数据。
                if (empty($cash_record) || $cash_record->cash_status != UserWalletCash::STATUS_REVIEW) {
                    return $status_result[$id] = 'failure';
                }
                $cash_record->cash_status = $cash_status;
                if ($cash_status == UserWalletCash::STATUS_REVIEWED) {
                    //检查证书
                    if (!file_exists(storage_path().'/cert/apiclient_cert.pem') || !file_exists(storage_path().'/cert/apiclient_key.pem')) {
                        throw new WalletException('pem_notexist');
                    }
                    //零钱付款
                    if ($cash_record->cash_type != UserWalletCash::TRANSFER_TYPE_MCH) {
                        return $status_result[$id] = 'failure';
                    }
                    //审核通过
                    if ($cash_record->save()) {
                        //触发提现钩子事件
                        $this->events->dispatch(
                            new Cash($cash_record, $this->ip_address, GatewayConfig::WECAHT_TRANSFER)
                        );
                        return $status_result[$id] = 'success';
                    }
                } elseif ($cash_status == UserWalletCash::STATUS_REVIEW_FAILED) {
                    $cash_apply_amount = $cash_record->cash_apply_amount;//提现申请金额
                    //审核不通过解冻金额
                    $user_id = $cash_record->user_id;
                    //开始事务
                    $this->connection->beginTransaction();
                    try {
                        //获取用户钱包
                        $user_wallet = UserWallet::lockForUpdate()->find($user_id);
                        //返回冻结金额至用户钱包
                        $user_wallet->freeze_amount    = $user_wallet->freeze_amount - $cash_apply_amount;
                        $user_wallet->available_amount = $user_wallet->available_amount + $cash_apply_amount;
                        $user_wallet->save();

                        //冻结变动金额，为负数数
                        $change_freeze_amount = -$cash_apply_amount;
                        //可用金额增加
                        $change_available_amount = $cash_apply_amount;
                        //添加钱包明细
                        $user_wallet_log = UserWalletLog::createWalletLog(
                            $user_id,
                            $change_available_amount,
                            $change_freeze_amount,
                            UserWalletLog::TYPE_CASH_THAW,
                            app('translator')->get('wallet.cash_review_failure'),
                            $cash_record->id
                        );

                        $cash_record->remark = Arr::get($this->data, 'remark', '');
                        $cash_record->refunds_status = UserWalletCash::REFUNDS_STATUS_YES;
                        $cash_record->save();
                        $this->connection->commit();
                        return $status_result[$id] = 'success';
                    } catch (Exception $e) {
                        //回滚事务
                        $this->connection->rollback();
                        throw new WalletException($e->getMessage(), 500);
                    }
                } elseif ($cash_status == UserWalletCash::STATUS_PAID) {
                    //人工打款
                    if ($cash_record->cash_type != UserWalletCash::TRANSFER_TYPE_MANUAL) {
                        return $status_result[$id] = 'failure';
                    }
                    //开始事务
                    $this->connection->beginTransaction();
                    try {
                        $cash_record->remark = Arr::get($this->data, 'remark', '');
                        $cash_record->cash_status = UserWalletCash::STATUS_PAID;//已打款
                        $cash_record->save();
                        //获取用户钱包
                        $user_wallet = UserWallet::lockForUpdate()->find($cash_record->user_id);
                        //去除冻结金额
                        $user_wallet->freeze_amount = $user_wallet->freeze_amount - $cash_record->cash_apply_amount;
                        $user_wallet->save();
                        //冻结变动金额，为负数
                        $change_freeze_amount = -$cash_record->cash_apply_amount;
                        //添加钱包明细
                        $user_wallet_log = UserWalletLog::createWalletLog(
                            $cash_record->user_id,
                            0,
                            $change_freeze_amount,
                            UserWalletLog::TYPE_CASH_SUCCESS,
                            app('translator')->get('wallet.cash_success'),
                            $cash_record->id
                        );
                        //提交事务
                        $this->connection->commit();
                        return $status_result[$id] = 'success';
                    } catch (\Exception $e) {
                        //回滚事务
                        $this->connection->rollback();
                        throw new WalletException($e->getMessage(), 500);
                    }
                }
                return $status_result[$id] = 'failure';
            });
        return $status_result;
    }
}
