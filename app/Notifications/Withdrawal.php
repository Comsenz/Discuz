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
        dd($this->cash);
        $build = [
            'user_id' => $this->order->user->id,  // 提现人ID
            'order_id' => $this->order->id,
            'thread_id' => $this->order->thread->id,   // 必传
            'thread_username' => $this->order->thread->user->username, // 必传主题用户名
            'thread_title' => $this->order->thread->title,
            'content' => '',  // 兼容原数据
            'thread_created_at' => $this->order->thread->created_at->toDateTimeString(),
            'amount' => $this->order->amount - $this->order->master_amount,
            'order_type' => $this->order->type,  // 1：注册，2：打赏，3：付费主题，4：付费用户组
        ];

        $this->build($build);

        return $build;
    }

    /**
     * @param $build
     */
    public function build(&$build)
    {
        $content = $this->order->thread->getContentByType(Thread::CONTENT_LENGTH);

        $build['content'] = $content;
    }

    /**
     * 设置驱动名称
     *
     * @param $strClass
     */
    protected function setChannelName($strClass)
    {
        switch ($strClass) {
            case 'App\MessageTemplate\Wechat\WechatRewardedMessage':
                $this->channel = 'wechat';
                break;
            case 'App\MessageTemplate\RewardedMessage':
            default:
                $this->channel = 'database';
                break;
        }
    }

}
