<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
        if (!in_array($cash_status, [UserWalletCash::STATUS_REVIEWED, UserWalletCash::STATUS_REVIEW_FAILED])) {
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
                    //审核通过
                    //触发提现钩子事件
                    $this->events->dispatch(
                        new Cash($cash_record, $this->ip_address, GatewayConfig::WECAHT_TRANSFER)
                    );
                    if ($cash_record->save()) {
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
                        $user_wallet_log = UserWalletLog::createWalletLog($user_id, $change_available_amount, $change_freeze_amount, UserWalletLog::TYPE_CASH_THAW, app('translator')->get('wallet.cash_review_failure'));

                        $cash_record->remark = Arr::get($this->data, 'remark');
                        $cash_record->refunds_status = UserWalletCash::REFUNDS_STATUS_YES;
                        $cash_record->save();
                        $this->connection->commit();
                        return $status_result[$id] = 'success';
                    } catch (Exception $e) {
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
