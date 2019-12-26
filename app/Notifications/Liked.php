<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class Liked extends Notification
{
    use Queueable;

    public $post;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
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
            'thread_id' => $this->post->thread->id,
            'thread_title' => $this->post->thread->title,
            'post_id' => $this->post->id,
            'post_content' => $this->post->formatContent(),
            'user_id' => $this->post->user->id,
            'user_name' => $this->post->user->username,
            'user_avatar' => $this->post->user->avatar,
        ];
    }
}
