<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Thread;

use App\Events\Post\Created as PostCreated;
use App\Events\Thread\Created as ThreadCreated;
use App\Events\Thread\Deleted;
use App\Events\Thread\Hidden;
use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
use App\Models\Post;
use App\Models\PostMod;
use App\Models\Thread;
use App\Models\UserActionLogs;
use App\Traits\ThreadNoticesTrait;
use App\Traits\ThreadTrait;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;

class ThreadListener
{
    use ThreadTrait;
    use ThreadNoticesTrait;

    public function subscribe(Dispatcher $events)
    {
        // 分类主题
        $events->listen(Saving::class, SaveCategoryToDatabase::class);

        // 发布帖子
        $events->listen(PostCreated::class, [$this, 'whenPostWasCreated']);
        $events->listen(ThreadCreated::class, [$this, 'threadCreated']);

        // 审核主题
        $events->listen(ThreadWasApproved::class, [$this, 'whenThreadWasApproved']);

        // 隐藏主题
        $events->listen(Hidden::class, [$this, 'whenThreadWasHidden']);

        // 删除主题
        $events->listen(Deleted::class, [$this, 'whenThreadWasDeleted']);

        // 收藏主题
        $events->listen(Serializing::class, AddThreadFavoriteAttribute::class);
        $events->listen(Saving::class, SaveFavoriteToDatabase::class);

        // 通知主题
        $events->listen(ThreadNotices::class, [$this, 'whenThreadNotices']);
    }

    /**
     * 发布首帖时，更新主题回复数，最后回复 ID
     *
     * @param PostCreated $event
     */
    public function whenPostWasCreated(PostCreated $event)
    {
        $thread = $event->post->thread;

        if ($thread && $thread->exists) {
            $thread->refreshPostCount();
            $thread->refreshLastPost();
            $thread->save();
        }
    }

    /**
     * 主题发布后，增加分类主题数量
     *
     * @param ThreadCreated $event
     * @throws \App\Exceptions\ThreadException
     */
    public function threadCreated(ThreadCreated $event)
    {
        $this->action($event->thread, 'create');
    }

    /**
     * 审核主题时，记录操作
     *
     * @param ThreadWasApproved $event
     */
    public function whenThreadWasApproved(ThreadWasApproved $event)
    {
        // 审核通过时，清除记录的敏感词
        if ($event->thread->is_approved == Thread::APPROVED) {
            PostMod::where('post_id', $event->thread->firstPost->id)->delete();
        }

        $action = $this->transLogAction($event->thread->is_approved);

        $message = $event->data['message'] ?? '';

        UserActionLogs::writeLog($event->actor, $event->thread, $action, $message);

        // 发送操作通知
        $this->threadNotices($event->data['notice_type'], $event);
    }

    /**
     * 隐藏主题时，记录操作
     *
     * @param Hidden $event
     */
    public function whenThreadWasHidden(Hidden $event)
    {
        $action = 'hide';

        $message = $event->data['message'] ?? '';

        UserActionLogs::writeLog($event->actor, $event->thread, $action, $message);

        // 发送删除通知
        $this->threadNotices('isDeleted', $event);
    }

    /**
     * 删除主题时，删除主题下所有回复
     *
     * @param Deleted $event
     */
    public function whenThreadWasDeleted(Deleted $event)
    {
        Post::where('thread_id', $event->thread->id)->delete();
    }

    /**
     * 操作主题时，发送对应通知
     *
     * @param $noticeType
     * @param $event
     */
    public function threadNotices($noticeType, $event)
    {
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
}
