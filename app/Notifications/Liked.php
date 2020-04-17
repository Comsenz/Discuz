<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;

class Liked extends System
{
    use Queueable;

    public $post;

    public $actor;

    /**
     * 当前驱动名称
     * @var
     */
    public $channel;

    /**
     * LikedTest constructor.
     *
     * @param Post $post
     * @param $actor
     * @param $likedMessageClass
     * @param $build
     */
    public function __construct(Post $post, $actor, $likedMessageClass, $build)
    {
        $this->setChannelName($likedMessageClass);

        parent::__construct($likedMessageClass, $build);

        $this->post = $post;
        $this->actor = $actor;
    }

    public function toDatabase($notifiable)
    {
        // 长文点赞通知内容为标题
        if ($this->post->thread->type == 1) {
            $content = htmlspecialchars($this->post->thread->title);
        } else {
            // 引用回复去除引用部分
            if ($this->post->reply_post_id) {
                $pattern = '/<blockquote class="quoteCon">.*<\/blockquote>/';
                $this->post->content = preg_replace($pattern, '', $this->post->content);
            }

            $this->post->content = Str::limit($this->post->content, Post::SUMMARY_LENGTH);

            $content = $this->post->formatContent();
        }

        return [
            'thread_id' => $this->post->thread->id,
            'thread_title' => htmlspecialchars($this->post->thread->title),
            'post_id' => $this->post->id,
            'post_content' => $content,
            'user_id' => $this->actor->id,
            'user_name' => $this->actor->username,
            'user_avatar' => $this->actor->avatar ? $this->actor->avatar . '?' . Carbon::parse($this->actor->avatar_at)->timestamp : '',
        ];
    }

    public function toWechat($notifiable)
    {
        return $this->message->notifiable($notifiable)->template($this->data);
    }

    /**
     * 设置驱动名称
     *
     * @param $strClass
     */
    protected function setChannelName($strClass)
    {
        switch ($strClass) {
            case 'App\MessageTemplate\Wechat\WechatLikedMessage':
                $this->channel = 'wechat';
                break;
            default:
                $this->channel = 'database';
                break;
        }
    }
}
