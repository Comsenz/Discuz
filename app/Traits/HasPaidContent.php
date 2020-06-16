<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Traits;

use App\Models\Attachment;
use App\Models\Post;
use App\Models\Thread;
use App\Models\ThreadVideo;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @package App\Traits
 */
trait HasPaidContent
{
    /**
     * @var array
     */
    protected $threads = [];

    /**
     * @var array
     */
    protected $orders = [];

    /**
     * @param Post|Attachment|ThreadVideo $model
     */
    public function paidContent($model)
    {
        /** @var User $actor */
        $actor = $this->actor;

        Thread::setStateUser($actor);

        // 作者本人 或 管理员 不处理（新增类型时请保证 $model->user_id 存在）
        if ($actor->id === $model->user_id || $actor->isAdmin()) {
            return;
        }

        if ($model instanceof Post) {
            $this->summaryOfContent($model);
        } elseif ($model instanceof Attachment) {
            $this->blurImage($model);
        } elseif ($model instanceof ThreadVideo) {
            $this->hideMedia($model);
        }
    }

    /**
     * 付费长文帖未付费时返回免费部分内容，不返回图片及附件
     *
     * @param Post $post
     */
    public function summaryOfContent(Post $post)
    {
        if (
            $post->is_first
            && $post->thread->price > 0
            && ! $post->thread->is_paid
        ) {
            $content = Str::of($post->content);

            // 截取内容
            if ($content->length() > Post::SUMMARY_LENGTH) {
                $post->content = $content->substr(0, $post->thread->free_words)->finish(Post::SUMMARY_END_WITH);
            }

            // 帖子的 images 与 attachments 不在序列化 Attachment 时处理，直接设为空
            if ($post->thread->type === Thread::TYPE_OF_LONG) {
                $post->setRelation('images', collect());
                $post->setRelation('attachments', collect());
            }
        }
    }

    /**
     * 付费图片帖未付费时返回模糊图
     *
     * @param Attachment $attachment
     */
    public function blurImage(Attachment $attachment)
    {
        if (
            $attachment->type === Attachment::TYPE_OF_IMAGE
            && $attachment->post->is_first
            && $attachment->post->thread->type === Thread::TYPE_OF_IMAGE
            && $attachment->post->thread->price > 0
            && ! $attachment->post->thread->is_paid
        ) {
            $attachment->setAttribute('blur', true);

            $parts = explode('.', $attachment->attachment);
            $parts[0] = md5($parts[0]);

            $attachment->attachment = implode('_blur.', $parts);
        }
    }

    /**
     * 付费视频帖未付费时不返回媒体 id 及地址
     *
     * @param ThreadVideo $threadVideo
     */
    public function hideMedia(ThreadVideo $threadVideo)
    {
        if (
            $threadVideo->type === ThreadVideo::TYPE_OF_VIDEO
            && $threadVideo->thread->type === Thread::TYPE_OF_VIDEO
            && $threadVideo->thread->price > 0
            && ! $threadVideo->thread->is_paid
        ) {
            $threadVideo->file_id = '';
            $threadVideo->media_url = '';
        }
    }
}
