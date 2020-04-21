<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Order;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

/**
 * 打赏通知
 *
 * Class Rewarded
 * @package App\Notifications
 */
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
        if ($this->order->thread->type == 1) {
            $content = htmlspecialchars($this->order->thread->title);
        } else {
            $this->order->thread->firstPost->content =
                Str::limit($this->order->thread->firstPost->content, Post::SUMMARY_LENGTH);

            $content = $this->order->thread->firstPost->formatContent();
        }

        // 'thread_created_at' => $this->order->thread->created_at),
        return [
            'user_id' => $this->order->user->id,
            'order_id' => $this->order->id,
            'thread_id' => $this->order->thread->id,
            'thread_title' => htmlspecialchars($this->order->thread->title),
            'content' => $content,
            'amount' => $this->order->amount - $this->order->master_amount,
        ];
    }
}
