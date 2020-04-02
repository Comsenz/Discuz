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
        $content = $this->order->thread->type == 1
            ? $this->order->thread->title
            : $this->order->thread->firstPost->formatContent();

        return [
            'order_id' => $this->order->id,
            'thread_id' => $this->order->thread->id,
            'thread_title' => htmlspecialchars($this->order->thread->title),
            'content' => htmlspecialchars($content),
            'amount' => $this->order->amount - $this->order->master_amount,
            'user_id' => $this->order->user->id,
            'user_name' => $this->order->user->username,
            'user_avatar' => $this->order->user->avatar,
        ];
    }
}
