<?php
declare(strict_types = 1);

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
        $operate_reason = Arr::get($this->data, 'operate_reason');
        $wallet_status  = Arr::get($this->data, 'wallet_status');

        if (!in_array($operate_type, [UserWallet::OPERATE_ADD, UserWallet::OPERATE_REDUCE])) {
            throw new WalletException('operate_type_error');
        }

        if (!is_null($wallet_status) && !in_array($wallet_status, [UserWallet::WALLET_STATUS_NORMAL, UserWallet::WALLET_STATUS_FROZEN])) {
            throw new WalletException('wallet_status_error');
        }
        //操作金额
        $change_available_amount = sprintf('%.2f', floatval($operate_amount));
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'UpdateUserWallet');
        //开始事务
        $db->beginTransaction();
        try {
            $user_wallet = UserWallet::lockForUpdate()->findOrFail($this->user_id);
            switch ($operate_type) {
                case UserWallet::OPERATE_ADD: //增加
                    break;
                case UserWallet::OPERATE_REDUCE: //减少
                    if ($user_wallet->available_amount - $operate_amount < 0) {
                        throw new Exception('available_amount_error');
                    }
                    $change_available_amount = -$change_available_amount;
                    break;
                default:
                    throw new Exception('operate_type_error');
                    break;
            }
            //修改钱包金额
            $user_wallet->available_amount = sprintf('%.2f', ($user_wallet->available_amount + $change_available_amount));
            //钱包状态修改
            if (!is_null($wallet_status)) {
                $user_wallet->wallet_status = (int) $wallet_status;
            }
            $user_wallet->save();
            //添加钱包明细
            $user_wallet_log = UserWalletLog::createWalletLog(
                $this->user_id,
                $change_available_amount,
                0,
                UserWalletLog::TYPE_EXPEND_ARTIFICIAL,
                $operate_reason
            );

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
