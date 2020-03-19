<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Console\Commands;

use App\Models\Finance;
use App\Models\Order;
use App\Models\UserWalletCash;
use Carbon\Carbon;
use Discuz\Console\AbstractCommand;

class FinanceCreate extends AbstractCommand
{
    protected $signature = 'finance:create';

    protected $description = 'Count the financial situation of the previous day.';

    protected $finance;

    protected $order;

    protected $userWalletCash;

    public function __construct(Finance $finance, Order $order, UserWalletCash $userWalletCash)
    {
        $this->finance = $finance;
        $this->order = $order;
        $this->userWalletCash = $userWalletCash;

        parent::__construct();
    }

    public function handle()
    {
        $date = Carbon::parse('-1 day')->toDateString();
        $dateTimeBegin = $date . ' 00:00:00';
        $dateTimeEnd = $date . ' 23:59:59';

        $register_profit = $this->order::WhereBetween('created_at', [$dateTimeBegin, $dateTimeEnd])->where('type', 1)->where('status', $this->order::ORDER_STATUS_PAID)->sum('amount');
        $master_portion = $this->order::WhereBetween('created_at', [$dateTimeBegin, $dateTimeEnd])->where('status', $this->order::ORDER_STATUS_PAID)->sum('master_amount');
        $withdrawal_profit = $this->userWalletCash::WhereBetween('created_at', [$dateTimeBegin, $dateTimeEnd])->where('cash_status', $this->userWalletCash::STATUS_PAID)->sum('cash_charge');
        $order_amount = $this->order::WhereBetween('created_at', [$dateTimeBegin, $dateTimeEnd])->where('status', $this->order::ORDER_STATUS_PAID)->sum('amount');
        $order_count = $this->order::WhereBetween('created_at', [$dateTimeBegin, $dateTimeEnd])->count();
        $withdrawal = $this->userWalletCash::WhereBetween('created_at', [$dateTimeBegin, $dateTimeEnd])->where('cash_status', $this->userWalletCash::STATUS_PAID)->sum('cash_apply_amount');
        $income = $this->order::WhereBetween('created_at', [$dateTimeBegin, $dateTimeEnd])->where('status', $this->order::ORDER_STATUS_PAID)->sum('amount');

        $this->finance->updateOrCreate(
            ['created_at' => $date],
            [
                'income' => $income,
                'withdrawal' => $withdrawal,
                'order_count' => $order_count,
                'order_amount' => $order_amount,
                'total_profit' => $register_profit + $master_portion + $withdrawal_profit,
                'register_profit' => $register_profit,
                'master_portion' => $master_portion,
                'withdrawal_profit' => $withdrawal_profit
            ]
        );

        $this->info($date . ' ' . $this->signature);
    }
}
