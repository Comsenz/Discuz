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

class FinanceProfile
{
    use AssertPermissionTrait;

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

        $financeProfile = [];
        //用户总充值
        data_set(
            $financeProfile,
            'totalIncome',
            $this->order::where('status', $this->order::ORDER_STATUS_PAID)->sum('amount')
        );
        //用户总提现
        data_set(
            $financeProfile,
            'totalWithdrawal',
            $this->userWalletCash::where('cash_status', $this->userWalletCash::STATUS_PAID)->sum('cash_apply_amount')
        );
        //用户钱包总金额
        $userWallet = $this->userWallet::selectRaw('SUM(available_amount) as available_amount')
            ->selectRaw('SUM(freeze_amount) as freeze_amount')
            ->first()
            ->toArray();
        data_set(
            $financeProfile,
            'totalWallet',
            $userWallet['available_amount'] + $userWallet['freeze_amount']
        );
        //提现手续费收入
        data_set(
            $financeProfile,
            'withdrawalProfit',
            $this->userWalletCash::where('cash_status', $this->userWalletCash::STATUS_PAID)->sum('cash_charge')
        );
        //打赏提成收入
        data_set(
            $financeProfile,
            'orderRoyalty',
            $this->order::where('status', $this->order::ORDER_STATUS_PAID)->sum('master_amount')
        );
        //注册加入收入
        data_set(
            $financeProfile,
            'totalRegisterProfit',
            $this->order::where('type', 1)->where('status', $this->order::ORDER_STATUS_PAID)->sum('amount')
        );
        //平台总盈利：注册加入收入+打赏提成收入+提现手续费收入
        data_set(
            $financeProfile,
            'totalProfit',
            Arr::get($financeProfile, 'totalRegisterProfit') +
            Arr::get($financeProfile, 'orderRoyalty') +
            Arr::get($financeProfile, 'withdrawalProfit')
        );
        //用户订单总数
        data_set(
            $financeProfile,
            'orderCount',
            $this->order::count()
        );

        return $financeProfile;
    }
}
