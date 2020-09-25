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

use App\Exceptions\WalletException;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use Discuz\Auth\AssertPermissionTrait;

class UpdateUserWallet
{
    use AssertPermissionTrait;

    /**
     * 钱包用户ID
     * @var int
     */
    public $user_id;

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
     * 初始化命令参数
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct($user_id, $actor, Collection $data)
    {
        $this->user_id = $user_id;
        $this->actor   = $actor;
        $this->data    = $data->toArray();
    }

    /**
     * 执行命令
     * @return model UserWallet
     * @throws Exception
     */
    public function handle(Validator $validator, ConnectionInterface $db)
    {
        $this->assertCan($this->actor, 'wallet.update');
        // 验证参数
        $validator_info = $validator->make($this->data, [
            'operate_type'   => 'sometimes|required|integer', //操作类型，1：增加；2：增加
            'operate_amount' => 'sometimes|required|numeric|min:0.01', //操作金额
            'wallet_status'  => 'sometimes|required|integer', //钱包状态
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        $operate_type   = Arr::get($this->data, 'operate_type');
        $operate_amount = Arr::get($this->data, 'operate_amount');
        $operate_reason = Arr::get($this->data, 'operate_reason', '');
        $operate_reason = trim($operate_reason);
        $wallet_status  = Arr::get($this->data, 'wallet_status');
        if (!is_null($operate_type)) {
            if (!in_array($operate_type, [UserWallet::OPERATE_INCREASE, UserWallet::OPERATE_DECREASE])) {
                throw new WalletException('operate_type_error');
            }
        }

        if (!is_null($wallet_status) && !in_array($wallet_status, [UserWallet::WALLET_STATUS_NORMAL, UserWallet::WALLET_STATUS_FROZEN])) {
            throw new WalletException('wallet_status_error');
        }
        //操作金额
        $change_available_amount = sprintf('%.2f', floatval($operate_amount));
        //开始事务
        $db->beginTransaction();
        $change_type = '';
        try {
            $user_wallet = UserWallet::lockForUpdate()->findOrFail($this->user_id);
            switch ($operate_type) {
                case UserWallet::OPERATE_INCREASE: //增加
                    $change_type = UserWalletLog::TYPE_INCOME_ARTIFICIAL;
                    if (!strlen($operate_reason)) {
                        $operate_reason = app('translator')->get('wallet.income_artificial');
                    }
                    break;
                case UserWallet::OPERATE_DECREASE: //减少
                    if ($user_wallet->available_amount - $operate_amount < 0) {
                        throw new Exception('available_amount_error');
                    }
                    if (!strlen($operate_reason)) {
                        $operate_reason = app('translator')->get('wallet.expend_artificial');
                    }
                    $change_available_amount = -$change_available_amount;
                    $change_type = UserWalletLog::TYPE_EXPEND_ARTIFICIAL;
                    break;
                default:
                    break;
            }
            //钱包状态修改
            if (!is_null($wallet_status)) {
                $user_wallet->wallet_status = (int) $wallet_status;
            }
            //金额变动
            if ($change_type) {
                //修改钱包金额
                $user_wallet->available_amount = sprintf('%.2f', ($user_wallet->available_amount + $change_available_amount));
                //添加钱包明细
                $user_wallet_log = UserWalletLog::createWalletLog(
                    $this->user_id,
                    $change_available_amount,
                    0,
                    $change_type,
                    $operate_reason
                );
            }
            $user_wallet->save();
            //提交事务
            $db->commit();
            return $user_wallet;
        } catch (Exception $e) {
            //回滚事务
            $db->rollback();
            throw new WalletException($e->getMessage(), 500);
        }
    }
}
