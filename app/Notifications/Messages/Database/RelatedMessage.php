<?php

namespace App\Notifications\Messages\Database;

use App\Models\Post;
use Discuz\Notifications\Messages\SimpleMessage;

class RelatedMessage extends SimpleMessage
{
    protected $post;

    protected $actor;

    public function __construct()
    {
        //
    }

    public function setData(...$parameters)
    {
        // 解构赋值
        [$firstData, $actor, $post] = $parameters;
        // set parent tpl data
        $this->firstData = $firstData;

        $this->actor = $actor;
        $this->post = $post;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    public function contentReplaceVars($data)
    {
        return $data;
    }

    public function render()
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

        $this->changeBuild($build);

        return $build;
    }

    /**
     * @param & $build
     */
    public function changeBuild(&$build)
    {
        $result = $this->post->getSummaryContent(Post::NOTICE_LENGTH);

        /**
         * 判断是否是楼中楼的回复
         */
        if ($this->post->reply_post_id) {
            $build['post_content'] = $result['content'];
            $build['post_created_at'] = $this->post->formatDate('created_at');
            // 回复的楼中楼数据
            $build['reply_post_id'] = $this->post->reply_post_id;
            $build['reply_post_user_id'] = $this->post->replyPost->user_id;
            $build['reply_post_content'] = $this->post->replyPost->formatContent();
            $build['reply_post_created_at'] = $this->post->replyPost->formatDate('created_at');
        } else {
            /**
             * 长文点赞通知内容为标题
             */
            $content = $result['content'];

            // 不是长文没有标题则使用首贴内容
            $firstContent = $result['first_content'];

            $build['post_content'] = $content == $firstContent ? '' : $content ;
            $build['post_created_at'] = $this->post->formatDate('created_at');
        }

        // 主题数据
        $build['thread_id'] = $this->post->thread->id;
        $build['thread_username'] = $this->post->thread->user->username;
        $build['thread_title'] = $firstContent ?? $result['first_content'];
        $build['thread_created_at'] = $this->post->thread->formatDate('created_at');
    }
}
