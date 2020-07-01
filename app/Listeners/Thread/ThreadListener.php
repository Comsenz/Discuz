<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Thread;

use App\Events\Thread\Created;
use App\Events\Thread\Deleted;
use App\Events\Thread\Hidden;
use App\Events\Thread\Restored;
use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
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

        // 隐藏/还原主题
        $events->listen(Hidden::class, [$this, 'whenThreadWasHidden']);
        $events->listen(Restored::class, [$this, 'whenThreadWasRestored']);

        // 删除主题
        $events->listen(Deleted::class, [$this, 'whenThreadWasDeleted']);

        // 收藏主题
        $events->listen(Serializing::class, AddThreadFavoriteAttribute::class);
        $events->listen(Saving::class, SaveFavoriteToDatabase::class);

        // 通知主题
        $events->listen(ThreadNotices::class, [$this, 'whenThreadNotices']);
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
        // 审核通过时
        if ($event->thread->is_approved === Thread::APPROVED) {
            // 清除记录的敏感词
            PostMod::query()->where('post_id', $event->thread->firstPost->id)->delete();

            // 创建话题和关系
            $event->thread->firstPost->setContentAttribute($event->thread->firstPost->content);
            $event->thread->firstPost->save();
            ThreadTopic::setThreadTopic($event->thread->firstPost);
        }

        $this->updateThreadCount($event->thread);

        // 发送操作通知
        $this->threadNotices($event->data['notice_type'], $event);

        // 日志
        $action = UserActionLogs::$behavior[$event->thread->is_approved] ?? ('unknown' . $event->thread->is_approved);

        UserActionLogs::writeLog($event->actor, $event->thread, $action, $event->data['message'] ?? '');
    }

    /**
     * 隐藏主题时
     *
     * @param Hidden $event
     */
    public function whenThreadWasHidden(Hidden $event)
    {
        $thread = $event->thread;

        // 同步隐藏首帖
        $thread->firstPost->deleted_at = $thread->deleted_at;

        $thread->firstPost->save();

        $this->updateThreadCount($thread);

        // 通知
        $this->threadNotices('isDeleted', $event);

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

        // 同步还原首帖
        $thread->firstPost->deleted_at = null;

        $thread->firstPost->save();

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
     * 操作主题时，发送对应通知
     *
     * @param $noticeType
     * @param $event
     */
    public function threadNotices($noticeType, $event)
    {
        // 判断是否是审核通过操作 检索内容发送@通知
        if ($noticeType == 'isApproved' && $event->thread->is_approved == Thread::APPROVED) {
            $this->sendRelated($event->thread->firstPost, $event->thread->user);
        }

        // 判断是修改自己的主题 则不发送通知
        if ($event->thread->user_id == $event->actor->id) {
            return;
        }

        switch ($noticeType) {
            case 'isApproved':  // 内容审核通知
                $this->sendIsApproved($event->thread, ['refuse' => $this->reasonValue($event->data)]);
                break;
            case 'isSticky':    // 内容置顶通知
                $this->sendIsSticky($event->thread);
                break;
            case 'isEssence':   // 内容精华通知
                $this->sendIsEssence($event->thread);
                break;
            case 'isDeleted':   // 内容删除通知
                $this->sendIsDeleted($event->thread, ['refuse' => $this->reasonValue($event->data)]);
                break;
        }
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

            // 用户主题数
            $user = $thread->user;

            if ($user && $user->exists) {
                $user->refreshThreadCount()->save();
            }

            // 分类主题数
            $category = $thread->category;

            if ($category && $category->exists) {
                $category->refreshThreadCount()->save();
            }
        }
    }
}
