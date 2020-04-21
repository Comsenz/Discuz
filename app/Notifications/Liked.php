<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Discuz\SpecialChar\SpecialCharServer;

/**
 * 点赞通知
 *
 * Class Liked
 * @package App\Notifications
 */
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
     * @var
     * @method purify()
     */
    public $special;

    /**
     * LikedTest constructor.
     *
     * @param Post $post
     * @param $actor
     * @param $likedMessageClass
     * @param $build
     */
    public function __construct(Post $post, $actor, $likedMessageClass = '', $build = [])
    {
        $this->setChannelName($likedMessageClass);

        $this->post = $post;
        $this->actor = $actor;

        parent::__construct($likedMessageClass, $build);
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
        $this->special = app()->make(SpecialCharServer::class);

        $build = [
            'user_id' => $this->actor->id,
            'thread_id' => 0,
            'thread_user_id' => 0,
            'thread_title' => '',
            'thread_created_at' => '',
            'post_id' => $this->post->id,
            'post_content' => '',
            'reply_post_id' => 0, // 根据该字段判断是否是楼中楼
            'post_created_at' => '',
        ];

        $this->build($build);

        return $build;
    }

    /**
     * @param & $build
     */
    public function build(&$build)
    {
        /**
         * 判断是否是楼中楼的回复
         */
        if ($this->post->reply_post_id) {

            $this->post->content = $this->strOf($this->post->content);
            $content = $this->post->formatContent();

            $build['post_content'] = $content;
            $build['reply_post_id'] = $this->post->reply_post_id;
            $build['post_created_at'] = $this->post->created_at->toDateTimeString();

        } else {
            /**
             * 判断长文点赞通知内容为标题
             */
            if ($this->post->thread->type == 1) {
                $content = $this->strOf($this->post->thread->title);
                $content = $this->special->purify($content);
            } else {
                // 引用回复去除引用部分
                if ($this->post->reply_post_id) {
                    $pattern = '/<blockquote class="quoteCon">.*<\/blockquote>/';
                    $this->post->content = preg_replace($pattern, '', $this->post->content);
                }

                $this->post->content = $this->strOf($this->post->content);
                $content = $this->post->formatContent();

                // 不是长文没有标题则使用首贴内容
                $this->post->thread->firstPost->content = $this->strOf($this->post->thread->firstPost->content);
                $firstContent = $this->post->thread->firstPost->formatContent();
            }

            $build['thread_id'] = $this->post->thread->id;
            $build['thread_user_id'] = $this->post->thread->user_id;
            $build['thread_title'] = $firstContent ?? $this->special->purify($this->post->thread->title);
            $build['thread_created_at'] = $this->post->thread->created_at->toDateTimeString();
            $build['post_content'] = $content;
            $build['post_created_at'] = $this->post->created_at->toDateTimeString();
        }
    }

    /**
     * 截取字数
     *
     * @param $string
     * @return \Illuminate\Support\Stringable
     */
    public function strOf($string)
    {
        return Str::of($string)->substr(0, 80);
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
