<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Order;
use App\Models\Thread;
use App\Models\UserWalletCash;
use Discuz\SpecialChar\SpecialCharServer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * 提现通知
 *
 * Class Withdrawal
 * @package App\Notifications
 */
class Withdrawal extends System
{
    use Queueable;

    public $cash;

    public $channel;

    /**
     * Withdrawal constructor.
     *
     * @param UserWalletCash $cash
     * @param string $messageClass
     * @param array $build
     */
    public function __construct(UserWalletCash $cash, $messageClass = '', $build = [])
    {
        $this->setChannelName($messageClass);

        $this->cash = $cash;

        parent::__construct($messageClass, $build);
    }

    /**
     * 数据库驱动通知
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->cash->user->id,  // 提现人ID
            'wallet_cash_id' => $this->cash->id, // 提现记录ID
            'cash_actual_amount' => $this->cash->cash_actual_amount, // 实际提现金额
            'cash_apply_amount' => $this->cash->cash_apply_amount,   // 提现申请金额
            'cash_status' => $this->cash->cash_status,
            'remark' => $this->cash->remark,
            'created_at' => $this->cash->created_at->toDateTimeString(),
        ];
    }

    /**
     * 设置驱动名称
     *
     * @param $strClass
     */
    protected function setChannelName($strClass)
    {
        switch ($strClass) {
            case 'App\MessageTemplate\Wechat\WechatWithdrawalMessage':
                $this->channel = 'wechat';
                break;
            case 'App\MessageTemplate\WithdrawalMessage':
            default:
                $this->channel = 'database';
                break;
        }
    }

}
