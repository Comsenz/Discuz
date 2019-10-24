<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCashUserWallet.php XXX 2019-10-23 16:00 zhouzhou $
 */

namespace App\Commands\Wallet;

use App\Exceptions\ErrorException;
use App\Models\UserWallet;
use App\Models\UserWalletCash;
use App\Models\UserWalletLog;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class CreateCashUserWallet
{
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
    public function __construct($actor, Collection $data)
    {
        $this->actor = $actor;
        $this->data  = $data;
    }

    /**
     * 执行命令
     * @return model UserWallet
     * @throws Exception
     */
    public function handle(Validator $validator, ConnectionInterface $db)
    {
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'createCircle');
        // 验证参数
        $validator_info = $validator->make($this->data->toArray(), [
            'cash_apply_amount' => 'required|min:1|max:5000',
            'remark'            => 'sometimes|max:255',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        //开始事务
        $db->beginTransaction();
        try {
            //获取用户钱包
            $user_wallet = UserWallet::where('user_id', $this->actor->id)->lockForUpdate()->first();
            //检查钱包是否允许提现,1:钱包已冻结
            if ($user_wallet->wallet_status == 1) {
                throw new Exception(app('translator')->get('wallet.status_cash_freeze'), 500);
            }

            //提现金额
            $cash_apply_amount = floatval(Arr::get($this->data, 'cash_apply_amount'));
            $cash_apply_amount = sprintf("%.2f", $cash_apply_amount);

            //检查金额是否足够
            if ($user_wallet->available_amount < $cash_apply_amount) {
                throw new Exception(app('translator')->get('wallet.available_amount_error'), 500);
            }

            //计算手续费
            $tax_ratio  = 0.01; //手续费率
            $tax_amount = $cash_apply_amount * $tax_ratio; //手续费
            $tax_amount = sprintf("%.2f", ceil($tax_amount * 100) / 100); //格式化手续费

            $user_id            = $this->actor->id;
            $user_wallet_id     = $user_wallet->id;
            $cash_sn            = $this->getCashSn();
            $cash_charge        = $tax_amount;
            $cash_actual_amount = $cash_apply_amount - $tax_amount;
            $cash_apply_amount  = $cash_apply_amount;
            $remark             = Arr::get($this->data, 'remark');

            //冻结钱包金额
            $user_wallet->available_amount = $user_wallet->available_amount - $cash_apply_amount;
            $user_wallet->freeze_amount    = $user_wallet->freeze_amount + $cash_apply_amount;
            $user_wallet->save();

            //添加钱包明细,
            $user_wallet_log = UserWalletLog::createWalletLog($this->actor->id, $user_wallet_id, 0, $cash_apply_amount, 10, app('translator')->get('wallet.cash_freeze_desc'));

            //创建提现记录
            $cash = UserWalletCash::createCash(
                $this->actor->id,
                $user_wallet_id,
                $cash_sn,
                $cash_charge,
                $cash_actual_amount,
                $cash_apply_amount,
                $remark);
            //提交事务
            $db->commit();
            return $cash;
        } catch (Exception $e) {
            //回滚事务
            $db->rollback();
            throw new ErrorException($e->getMessage(), 500);
        }

    }

    /**
     * 生成提现编号
     * @return string  18位字符串
     */
    public function getCashSn()
    {
        return date('Ymd')
        . str_pad(strval(mt_rand(1, 99)), 2, '0', STR_PAD_LEFT)
        . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

}
