<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateUserWallet.php XXX 2019-10-23 10:00 zhouzhou $
 */

namespace App\Commands\Wallet;

use App\Exceptions\ErrorException;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

class UpdateUserWallet
{
    /**
     * 钱包ID
     * @var int
     */
    public $wallet_id;
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
    public function __construct($wallet_id, $actor, Collection $data)
    {
        $this->wallet_id = $wallet_id;
        $this->actor     = $actor;
        $this->data      = $data->toArray();
    }

    /**
     * 执行命令
     * @return model UserWallet
     * @throws Exception
     */
    public function handle(Validator $validator, ConnectionInterface $db)
    {

        // 验证参数
        $validator_info = $validator->make($this->data, [
            'operate_type' => 'sometimes|required|integer|between:1,2',//操作类型，1：增加；2：增加
            'operate_amount' => 'sometimes|required|numeric|min:0.01',//操作金额
            'wallet_status' => 'sometimes|required|integer|between:0,1',//钱包状态
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'UpdateUserWallet');
        //开始事务
        $db->beginTransaction();
        try {
            $user_wallet   = UserWallet::findOrFail($this->wallet_id)->lockForUpdate()->first();
            //是否有修改
            $change_status = false;
            //加减操作
            if (isset($this->data['operate_type'])) {
                $operate_amount = Arr::get($this->data, 'operate_amount');
                if ($operate_amount <= 0) {
                    throw new Exception(app('translator')->get('wallet.operate_amount_error'), 500);
                }
                $change_available_amount = sprintf("%.2f", floatval($operate_amount));
                switch ($this->data['operate_type']) {
                    case '1': //增加
                        break;
                    case '2': //减少
                        if ($user_wallet->available_amount - $operate_amount < 0) {
                            throw new Exception(app('translator')->get('wallet.available_amount_error'), 500);
                        }
                        $change_available_amount = -$change_available_amount;
                        break;
                    default:
                        throw new Exception(app('translator')->get('wallet.operate_type_error'), 500);
                        break;
                }
                $operate_reason = Arr::get($this->data, 'operate_reason');
                //添加钱包明细
                $user_wallet_log = UserWalletLog::createWalletLog($this->actor->id, $this->wallet_id, $change_available_amount, 0, 50, $operate_reason);
                //修改钱包金额
                $user_wallet->available_amount = sprintf("%.2f", ($user_wallet->available_amount + $change_available_amount));
                $change_status                 = true;
            }
            //钱包状态修改
            if (isset($this->data['wallet_status'])) {
                $change_status              = true;
                $user_wallet->wallet_status = (int) $this->data['wallet_status'];
            }
            if ($change_status) {
                $user_wallet->save();
            }
            //提交事务
            $db->commit();
            return $user_wallet;
        } catch (Exception $e) {
            //回滚事务
            $db->rollback();
            throw new ErrorException($e->getMessage(), 500);
        }
    }
}
