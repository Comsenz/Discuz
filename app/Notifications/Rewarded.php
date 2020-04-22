<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Order;
use App\Models\Thread;
use Discuz\SpecialChar\SpecialCharServer;
use Illuminate\Bus\Queueable;

/**
 * 打赏通知
 *
 * Class Rewarded
 * @package App\Notifications
 */
class Rewarded extends System
{
    use Queueable;

    public $order;

    public $actor;

    public $channel;

    public $special;

    /**
     * Rewarded constructor.
     *
     * @param Order $order
     * @param $actor
     * @param string $messageClass
     * @param array $build
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(Order $order, $actor, $messageClass = '', $build = [])
    {
        $this->setChannelName($messageClass);

        $this->order = $order;
        $this->actor = $actor;

        $this->special = app()->make(SpecialCharServer::class);

        parent::__construct($messageClass, $build);
    }

    /**
     * 数据库驱动通知
     *
     * @param $notifiable
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function toDatabase($notifiable)
    {
        $build = [
            'user_id' => $this->order->user->id,  // 付款人ID
            'order_id' => $this->order->id,
            'thread_id' => $this->order->thread->id,
            'thread_title' => $this->special->purify($this->order->thread->title),
            'thread_created_at' => $this->order->thread->created_at->toDateTimeString(),
            'content' => '',
            'amount' => $this->order->amount - $this->order->master_amount,
        ];

        $this->build($build);

        return $build;
    }

    /**
     * @param $build
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
