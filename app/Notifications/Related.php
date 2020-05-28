<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;

/**
 * @通知
 *
 * Class Related
 * @package App\Notifications
 */
class Related extends System
{
    use Queueable;

    /**
     * @var Post
     */
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
     * Related constructor.
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
     */
    public function toDatabase($notifiable)
    {
        $build = [
            'user_id' => $this->actor->id,
            'thread_id' => 0, // 必传
            'thread_username' => '', // 必传主题用户名
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
            $build['post_content'] = $this->post->getSummaryContent(Post::NOTICE_LENGTH)['content'];
            $build['reply_post_id'] = $this->post->reply_post_id;
            $build['post_created_at'] = $this->post->created_at->toDateTimeString();
        } else {
            /**
             * 长文点赞通知内容为标题
             */
            $content = $this->post->getSummaryContent(Post::NOTICE_LENGTH)['content'];

            // 不是长文没有标题则使用首贴内容
            $firstContent = $this->post->getSummaryContent(Post::NOTICE_LENGTH)['first_content'];

            $build['thread_id'] = $this->post->thread->id;
            $build['thread_username'] = $this->post->thread->user->username;
            $build['thread_title'] = $firstContent;
            $build['thread_created_at'] = $this->post->thread->created_at->toDateTimeString();
            $build['post_content'] = $content == $firstContent ? '' : $content ;
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
            case 'App\MessageTemplate\Wechat\WechatRelatedMessage':
                $this->channel = 'wechat';
                break;
            case 'App\MessageTemplate\RelatedMessage':
            default:
                $this->channel = 'database';
                break;
        }
    }

}
