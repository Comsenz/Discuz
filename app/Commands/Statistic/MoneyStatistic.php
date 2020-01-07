<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Statistic;

use App\Models\Order;
use App\Models\User;
use App\Models\UserWalletCash;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;

class MoneyStatistic
{
    use AssertPermissionTrait;

    protected $app;

    protected $actor;

    protected $order;

    protected $userWalletCash;


    public function __construct(User $actor)
    {
        $this->actor    = $actor;
    }

    public function handle(Order $order, UserWalletCash $userWalletCash)
    {
        $this->order = $order;
        $this->userWalletCash = $userWalletCash;

        return call_user_func([$this, '__invoke']);
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     */
    public function __invoke()
    {
        $this->assertAdmin($this->actor);

        $moneyStatistic = [];

        $moneyStatistic['totalIncome'] = $this->order::where('status', $this->order::ORDER_STATUS_PAID)
            ->sum('amount');

        $moneyStatistic['freezingAmount'] = $this->userWalletCash::where('cash_status', $this->userWalletCash::STATUS_REVIEW)
            ->sum('cash_apply_amount');

        $moneyStatistic['totalExpenditures'] = $this->userWalletCash::where('cash_status', $this->userWalletCash::STATUS_PAID)
            ->sum('cash_apply_amount');

        return $moneyStatistic;
    }
}
