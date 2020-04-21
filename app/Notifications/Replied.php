<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;

/**
 * 回复通知
 *
 * Class Replied
 * @package App\Notifications
 */
class Replied extends System
{
    use Queueable;

    public $post;

    /**
     * @var
     */
    public $actor;

    /**
     * 当前驱动名称
     * @var
     */
    public $channel;

    /**
     * Replied constructor.
     *
     * @param Post $post
     * @param $actor
     * @param string $messageClass
     * @param array $build
     */
    public function __construct(Post $post, $actor, $messageClass = '', $build = [])
    {
        $this->setChannelName($messageClass);

        $this->post = $post;
        $this->actor = $actor;

        parent::__construct($messageClass, $build);
    }

    /**
     * @param $notifiable
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function toDatabase($notifiable)
    {
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function build(&$build)
    {
        /**
         * 判断是否是楼中楼的回复
         */
        if ($this->post->reply_post_id) {
            $build['post_content'] = $this->post->getSummaryContent(80)['content'];
            $build['reply_post_id'] = $this->post->reply_post_id;
            $build['post_created_at'] = $this->post->created_at->toDateTimeString();
        } else {
            /**
             * 长文点赞通知内容为标题
             */
            $content = $this->post->getSummaryContent(80)['content'];

            // 不是长文没有标题则使用首贴内容
            $firstContent = $this->post->getSummaryContent(80)['first_content'];

            $build['thread_id'] = $this->post->thread->id;
            $build['thread_user_id'] = $this->post->thread->user_id;
            $build['thread_title'] = $firstContent;
            $build['thread_created_at'] = $this->post->thread->created_at->toDateTimeString();
            $build['post_content'] = $content;
            $build['post_created_at'] = $this->post->created_at->toDateTimeString();
        }
    }

    /**
     * 设置驱动名称
     *
     * @param $strClass
     */
    protected function setChannelName($strClass)
    {
        switch ($strClass) {
            case 'App\MessageTemplate\Wechat\WechatRepliedMessage':
                $this->channel = 'wechat';
                break;
            case 'App\MessageTemplate\RepliedMessage':
            default:
                $this->channel = 'database';
                break;
        }
    }

}
