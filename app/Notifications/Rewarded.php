<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class Rewarded extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase()
    {
        return [
            'order_id' => $this->order->id,
            'thread_id' => $this->order->thread->id,
            'thread_title' => $this->order->thread->title,
            'content' => $this->order->thread->firstPost->content,
            'amount' => $this->order->amount,
            'user_id' => $this->order->user->id,
            'user_name' => $this->order->user->username,
            'user_avatar' => $this->order->user->avatar,
        ];
    }
}
