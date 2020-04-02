<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class Replied extends Notification
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
        // 引用回复去除引用部分
        if ($this->post->reply_post_id) {
            $pattern = '/<blockquote class="quoteCon">.*<\/blockquote>/';
            $this->post->content = preg_replace($pattern, '', $this->post->content);
        }

        $this->post->content = Str::limit($this->post->content, Post::SUMMARY_LENGTH);

        $content = $this->post->formatContent();

        return [
            'thread_id' => $this->post->thread->id,
            'thread_title' => htmlspecialchars($this->post->thread->title),
            'post_id' => $this->post->id,
            'post_content' => $content,
            'user_id' => $this->post->user->id,
            'user_name' => $this->post->user->username,
            'user_avatar' => $this->post->user->avatar,
        ];
    }
}
