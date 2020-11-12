<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Traits;

use App\Models\Attachment;
use App\Models\Post;
use App\Models\Question;
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
     * @var User
     */
    protected $actor;

    /**
     * @var array
     */
    protected $threads = [];

    /**
     * @var array
     */
    protected $orders = [];

    /**
     * @param Thread|Post|Attachment|ThreadVideo|Question $model
     */
    public function paidContent($model)
    {
        Thread::setStateUser($this->actor);

        // 作者本人 或 管理员 不处理（新增类型时请保证 $model->user_id 存在）
        if ($this->actor->id === $model->user_id || $this->actor->isAdmin()) {
            return;
        }

        if ($model instanceof Thread) {
            $this->hideImagesAndAttachments($model);
            $this->getOnlookerState($model);
        } elseif ($model instanceof Post) {
            $this->summaryOfContent($model);
        } elseif ($model instanceof Attachment) {
            $this->blurImage($model);
        } elseif ($model instanceof ThreadVideo) {
            $this->hideMedia($model);
        } elseif ($model instanceof Question) {
            $this->hideAnswer($model);
        }
    }

    /**
     * 是否无权查看
     *
     * @param Thread $thread
     * @return bool
     */
    public function cannotView(Thread $thread)
    {
        // 不能查看详情
        $cannotViewPosts = ! $this->actor->can('viewPosts', $thread);

        // 不能免费查看付费内容
        $cannotFreeViewPosts = ! $this->actor->can('freeViewPosts.' . $thread->type, $thread);

        // （非站点推荐帖 且 不能查看详情）或（未付费 且 不能免费查看付费内容）
        return (! $thread->is_site && $cannotViewPosts)
            || ($thread->price > 0 && ! $thread->is_paid && $cannotFreeViewPosts);
    }

    /**
     * 付费长文帖未付费时不返回图片及附件
     * 帖子的 images 与 attachments 不在序列化 Attachment 时处理，直接设为空
     *
     * @param Thread $thread
     */
    public function hideImagesAndAttachments(Thread $thread)
    {
        if (
            $thread->type === Thread::TYPE_OF_LONG
            && $this->cannotView($thread)
        ) {
            $thread->firstPost->setRelation('images', collect());
            $thread->firstPost->setRelation('attachments', collect());
        }
    }

    /**
     * 获取问答帖围观状态
     *
     * @param Thread $thread
     */
    public function getOnlookerState(Thread $thread)
    {
        if ($thread->type !== Thread::TYPE_OF_QUESTION) {
            return;
        }

        if (! $thread->question) {
            $onlookerState = false;
        } elseif (! $thread->question->is_onlooker) {
            $onlookerState = true;
        } else {
            if (
                $thread->user_id === $this->actor->id
                || $thread->question->be_user_id === $this->actor->id
                || $this->actor->isAdmin()
                || $this->actor->can('freeViewPosts.' . Thread::TYPE_OF_QUESTION, $thread)
                || ! is_null($thread->onlookerState)
            ) {
                $onlookerState = true;
            } else {
                $onlookerState = false;
            }
        }

        $thread->setAttribute('onlookerState', $onlookerState);
    }

    /**
     * 付费长文帖未付费时返回免费部分内容
     *
     * @param Post $post
     */
    public function summaryOfContent(Post $post)
    {
        if (
            $post->is_first
            && $post->thread
            && $post->thread->type === Thread::TYPE_OF_LONG
            && $this->cannotView($post->thread)
        ) {
            $content = Str::of($post->content);
            if ($post->thread->free_words > 1) {
                $words = (int)$post->thread->free_words;
            } else {
                $words = ceil($content->length() * $post->thread->free_words);
            }
            $post->content = $content->substr(0, $words)->finish(Post::SUMMARY_END_WITH);
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
            is_null($attachment->getAttributeValue('blur'))
            && $attachment->type === Attachment::TYPE_OF_IMAGE
            && $attachment->post
            && $attachment->post->is_first
            && $attachment->post->thread
            && $attachment->post->thread->type === Thread::TYPE_OF_IMAGE
            && $this->cannotView($attachment->post->thread)
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
            && $threadVideo->thread
            && $threadVideo->thread->type === Thread::TYPE_OF_VIDEO
            && $this->cannotView($threadVideo->thread)
        ) {
            $threadVideo->file_id = '';
            $threadVideo->media_url = '';
        }
    }

    /**
     * 没有围观时隐藏问答帖答案
     *
     * @param Question $question
     */
    public function hideAnswer(Question $question)
    {
        $onlookerState = $question->thread->getAttribute('onlookerState')
            ?? $this->getOnlookerState($question->thread);

        if (! $onlookerState) {
            $question->content = '';
        }
    }
}
