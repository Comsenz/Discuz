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
        //用户总充值
        data_set(
            $financeStatistic,
            'totalIncome',
            $this->order::where('status', $this->order::ORDER_STATUS_PAID)->sum('amount')
        );
        //用户总提现
        data_set(
            $financeStatistic,
            'totalWithdrawal',
            $this->userWalletCash::where('cash_status', $this->userWalletCash::STATUS_PAID)->sum('cash_apply_amount')
        );
        //用户钱包总金额
        $userWallet = $this->userWallet::selectRaw('SUM(available_amount) as available_amount')
            ->selectRaw('SUM(freeze_amount) as freeze_amount')
            ->first()
            ->toArray();
        data_set(
            $financeStatistic,
            'totalWallet',
            $userWallet['available_amount'] + $userWallet['freeze_amount']
        );
        //提现手续费收入：(用户总提现 * 提现手续费百分比)
        data_set(
            $financeStatistic,
            'withdrawalProfit',
            $this->userWalletCash::where('cash_status', $this->userWalletCash::STATUS_PAID)->sum('cash_charge')
        );
        //订单提成收入：注册加入的收入+平台的分成收入
        $register_amount = $this->order::where('type', 1)->where('status', $this->order::ORDER_STATUS_PAID)->sum('amount');
        $master_amount = $this->order::where('status', $this->order::ORDER_STATUS_PAID)->sum('master_amount');
        data_set(
            $financeStatistic,
            'orderRoyalty',
            $register_amount + $master_amount
        );
        //平台总盈利：订单提成收入（打赏贴的分成 + 注册收入）+ 提现手续费收入
        data_set(
            $financeStatistic,
            'totalProfit',
            Arr::get($financeStatistic, 'orderRoyalty') + Arr::get($financeStatistic, 'withdrawalProfit')
        );
        //注册收入
        data_set(
            $financeStatistic,
            'totalRegisterProfit',
            $register_amount
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
