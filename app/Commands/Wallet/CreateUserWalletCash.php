<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Wallet;

use App\Exceptions\WalletException;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\UserWalletCash;
use App\Models\UserWalletLog;
use App\Settings\SettingsRepository;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use Exception;
use Discuz\Auth\AssertPermissionTrait;
use Carbon\Carbon;

class CreateUserWalletCash
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
     * 初始化命令参数
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct(User $actor, Collection $data)
    {
        $this->actor = $actor;
        $this->data  = $data;
    }

    /**
     * 执行命令
     * @return model UserWallet
     * @throws Exception
     */
    public function handle(Validator $validator, ConnectionInterface $db, SettingsRepository $setting)
    {
        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'cash.create');

        $cash_setting = $setting->tag('cash');
        $cash_interval_time = (int)Arr::get($cash_setting, 'cash_interval_time', 0);//提现间隔
        $cash_rate = (float)Arr::get($cash_setting, 'cash_rate', 0);//提现手续费
        $cash_sum_limit = (float)Arr::get($cash_setting, 'cash_sum_limit', 5000);//每日总提现额
        $cash_max_sum = (float)Arr::get($cash_setting, 'cash_max_sum', 5000);//每次最大金额
        $cash_min_sum = (float)Arr::get($cash_setting, 'cash_min_sum', 0);//每次最小金额
        // 验证参数
        $validator_info = $validator->make($this->data->toArray(), [
            'cash_apply_amount' => 'required|numeric|min:' . $cash_min_sum . '|max:' . $cash_max_sum,
            'remark'            => 'sometimes|max:255',
        ]);
        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        if ($cash_interval_time != 0) {
            $time_before = Carbon::now()->addDays(-$cash_interval_time);
            //提现间隔时间
            $cash_record = UserWalletCash::where('created_at', '>=', $time_before)->first();
            if (!empty($cash_record)) {
               throw new WalletException('cash_interval_time');
            }
        }
        //提现金额
        $cash_apply_amount = floatval(Arr::get($this->data, 'cash_apply_amount'));
        $cash_apply_amount = sprintf('%.2f', $cash_apply_amount);

        //今日已申提现总额
        $totday_cash_amount = UserWalletCash::where('created_at', '>=', Carbon::today())->sum('cash_apply_amount');
        if (($totday_cash_amount + $cash_apply_amount) > $cash_sum_limit) {
            //超出提现限额
            throw new WalletException('cash_sum_limit');
        }
        //计算手续费
        $tax_ratio  = $cash_rate; //手续费率
        $tax_amount = $cash_apply_amount * $tax_ratio; //手续费
        $tax_amount = sprintf('%.2f', ceil($tax_amount * 100) / 100); //格式化手续费

        $remark = Arr::get($this->data, 'remark');
        //开始事务
        $db->beginTransaction();
        try {
            //获取用户钱包
            $user_wallet = $this->actor->userWallet()->lockForUpdate()->first();
            //检查钱包是否允许提现,1:钱包已冻结
            if ($user_wallet->wallet_status == UserWallet::WALLET_STATUS_FROZEN) {
                throw new WalletException('status_cash_freeze');
            }
            //检查金额是否足够
            if ($user_wallet->available_amount < $cash_apply_amount) {
                throw new WalletException('available_amount_error');
            }
            $cash_sn            = $this->getCashSn();
            $cash_actual_amount = sprintf('%.2f', ($cash_apply_amount - $tax_amount));
            //创建提现记录
            $cash = UserWalletCash::createCash(
                $this->actor->id,
                $cash_sn,
                $tax_amount,
                $cash_actual_amount,
                $cash_apply_amount,
                $remark
            );
            //冻结钱包金额
            $user_wallet->available_amount = $user_wallet->available_amount - $cash_apply_amount;
            $user_wallet->freeze_amount    = $user_wallet->freeze_amount + $cash_apply_amount;

            $user_wallet->save();
            //添加钱包明细,
            $user_wallet_log = UserWalletLog::createWalletLog(
                $this->actor->id,
                -$cash_apply_amount,
                $cash_apply_amount,
                UserWalletLog::TYPE_CASH_FREEZE,
                app('translator')->get('wallet.cash_freeze_desc'),
                $cash->id
            );
            //提交事务
            $db->commit();
            return $cash;
        } catch (Exception $e) {
            //回滚事务
            $db->rollback();
            throw new WalletException($e->getMessage(), 500);
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
