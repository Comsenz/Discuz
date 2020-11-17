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

namespace App\Listeners\Thread;

use App\Events\Thread\Created;
use App\Events\Thread\Deleted;
use App\Events\Thread\Hidden;
use App\Events\Thread\Restored;
use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
use App\Events\Thread\ThreadWasCategorized;
use App\Listeners\User\CheckPublish;
use App\Models\Post;
use App\Models\PostMod;
use App\Models\Thread;
use App\Models\ThreadTopic;
use App\Models\UserActionLogs;
use App\Traits\PostNoticesTrait;
use App\Traits\ThreadNoticesTrait;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;

class ThreadListener
{
    use ThreadNoticesTrait;
    use PostNoticesTrait;

    public function subscribe(Dispatcher $events)
    {
        // 分类主题
        $events->listen(Saving::class, CheckPublish::class);
        $events->listen(Saving::class, SaveCategoryToDatabase::class);

        // 发布主题
        $events->listen(Created::class, [$this, 'whenThreadCreated']);

        // 审核主题
        $events->listen(ThreadWasApproved::class, [$this, 'whenThreadWasApproved']);

        // 分类主题
        $events->listen(ThreadWasCategorized::class, [$this, 'whenThreadWasCategorized']);

        // 隐藏/还原主题
        $events->listen(Hidden::class, [$this, 'whenThreadWasHidden']);
        $events->listen(Restored::class, [$this, 'whenThreadWasRestored']);

        // 删除主题
        $events->listen(Deleted::class, [$this, 'whenThreadWasDeleted']);

        // 收藏主题
        $events->listen(Serializing::class, AddThreadFavoriteAttribute::class);
        $events->listen(Saving::class, SaveFavoriteToDatabase::class);
    }

    /**
     * @param Created $event
     */
    public function whenThreadCreated(Created $event)
    {
        $this->updateThreadCount($event->thread);
    }

    /**
     * @param ThreadWasApproved $event
     */
    public function whenThreadWasApproved(ThreadWasApproved $event)
    {
        $thread = $event->thread;
        $firstPost = $event->thread->firstPost;

        // 审核通过
        if ($thread->is_approved === Thread::APPROVED) {
            // 清除记录的敏感词
            PostMod::query()->where('post_id', $firstPost->id)->delete();

            // 创建话题和关系
            $firstPost->setContentAttribute($firstPost->content);
            $firstPost->save();

            ThreadTopic::setThreadTopic($firstPost);
        }

        $this->updateThreadCount($thread);

        // 通知
        $this->threadNotices($thread, $event->actor, 'isApproved', $event->data['message'] ?? '');

        // 日志
        $action = UserActionLogs::$behavior[$thread->is_approved] ?? ('unknown' . $thread->is_approved);

        UserActionLogs::writeLog($event->actor, $thread, $action, $event->data['message'] ?? '');
    }

    /**
     * @param ThreadWasCategorized $event
     */
    public function whenThreadWasCategorized(ThreadWasCategorized $event)
    {
        $event->newCategory->refreshThreadCount()->save();
        $event->oldCategory && $event->oldCategory->refreshThreadCount()->save();
    }

    /**
     * 隐藏主题时
     *
     * @param Hidden $event
     */
    public function whenThreadWasHidden(Hidden $event)
    {
        $thread = $event->thread;

        $this->updateThreadCount($thread);

        // 通知
        $this->threadNotices($thread, $event->actor, 'isDeleted', $event->data['message'] ?? '');

        // 日志
        UserActionLogs::writeLog($event->actor, $thread, 'hide', $event->data['message'] ?? '');
    }

    /**
     * 还原主题时
     *
     * @param Restored $event
     */
    public function whenThreadWasRestored(Restored $event)
    {
        $thread = $event->thread;

        $this->updateThreadCount($thread);

        // 日志
        UserActionLogs::writeLog($event->actor, $thread, 'restore', $event->data['message'] ?? '');
    }

    /**
     * 删除主题时，删除主题下所有回复
     *
     * @param Deleted $event
     */
    public function whenThreadWasDeleted(Deleted $event)
    {
        Post::query()->where('thread_id', $event->thread->id)->delete();

        $this->updateThreadCount($event->thread);
    }

    /**
     * 更新主题数
     *
     * @param Thread $thread
     */
    private function updateThreadCount(Thread $thread)
    {
        if ($thread && $thread->exists) {
            // 主题回复数
            $thread->refreshPostCount();

            // 最新回复
            $thread->refreshLastPost();

            $thread->save();

            $user = $thread->user;
            if ($user && $user->exists) {
                // 用户主题数
                $user->refreshThreadCount();

                // 用户提问数
                if ($thread->type == Thread::TYPE_OF_QUESTION) {
                    $user->refreshQuestionCount();

                    if ($thread->question && $thread->question->beUser) {
                        $thread->question->beUser->refreshQuestionCount()->save();
                    }
                }

                $user->save();
            }

            // 分类主题数
            $category = $thread->category;

            if ($category && $category->exists) {
                $category->refreshThreadCount()->save();
            }
        }
    }
}
