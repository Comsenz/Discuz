<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Statistic;

use App\Models\Order;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\UserWalletCash;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;

class FinanceStatistic
{
    use AssertPermissionTrait;

    const WECHAT_RATE = 0.01;

    protected $app;

    protected $actor;

    protected $settings;

    protected $order;

    protected $userWallet;

    protected $userWalletCash;

    public function __construct(User $actor)
    {
        $this->actor    = $actor;
    }

    public function handle(Order $order, UserWallet $userWallet, UserWalletCash $userWalletCash, SettingsRepository $setting)
    {
        $this->order = $order;
        $this->userWallet = $userWallet;
        $this->userWalletCash = $userWalletCash;
        $this->settings = $setting;

        return call_user_func([$this, '__invoke']);
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     */
    public function __invoke()
    {
        $this->assertAdmin($this->actor);

        $financeStatistic = [];
        //用户总充值：注册+打赏的总额（未扣除手续费的）
        data_set(
            $financeStatistic,
            'totalIncome',
            $this->order::where('status', $this->order::ORDER_STATUS_PAID)->sum('amount')
        );
        //用户总提现：用户钱包已提现的总额
        data_set(
            $financeStatistic,
            'totalWithdrawal',
            $this->userWalletCash::where('cash_status', $this->userWalletCash::STATUS_PAID)->sum('cash_apply_amount')
        );
        //用户钱包总金额：用户钱包的汇总金额（未提现+冻结中）
        $userWallet = $this->userWallet::selectRaw('SUM(available_amount) as available_amount')
            ->selectRaw('SUM(freeze_amount) as freeze_amount')
            ->first()
            ->toArray();
        data_set(
            $financeStatistic,
            'totalWallet',
            $userWallet['available_amount'] + $userWallet['freeze_amount']
        );
        //平台手续费总支出：每次不管是注册还是打赏等，微信收的手续费的汇总
        data_set(
            $financeStatistic,
            'totalExpenditures',
            Arr::get($financeStatistic, 'totalIncome') * self::WECHAT_RATE
        );
        //提现手续费盈利：统一收用户的金额（提现手续费汇总）
        data_set(
            $financeStatistic,
            'withdrawalProfit',
            Arr::get($financeStatistic, 'totalWithdrawal') * ($this->settings->get('cash_rate')/100 - self::WECHAT_RATE)
        );
        //订单提成盈利：汇总平台的分成收入
        data_set(
            $financeStatistic,
            'orderRoyalty',
            $this->order::where('status', $this->order::ORDER_STATUS_PAID)->sum('master_amount')
        );
        //当前平台总盈利：(汇总平台的分成收入+注册收入)-(手续费) + 用户提现手续费
        data_set(
            $financeStatistic,
            'totalProfit',
            Arr::get($financeStatistic, 'orderRoyalty') * (1 - self::WECHAT_RATE) +
                    $this->order::where('status', $this->order::ORDER_STATUS_PAID)->where('type', $this->order::ORDER_TYPE_REGISTER)->sum('amount') * (1 - self::WECHAT_RATE)  +
                    Arr::get($financeStatistic, 'withdrawalProfit')
        );
        //当前订单总数：统计订单数量
        data_set(
            $financeStatistic,
            'orderCount',
            $this->order::count()
        );
        return $financeStatistic;
    }
}
