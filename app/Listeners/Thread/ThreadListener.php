<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadListener.php xxx 2019-10-18 12:14:00 LiuDongdong $
 */

namespace App\Listeners\Thread;

use App\Events\Post\Created as PostCreated;
use App\Events\Thread\Created as ThreadCreated;
use App\Events\Thread\Deleted;
use App\Events\Thread\Hidden;
use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use App\Models\OperationLog;
use App\Models\Post;
use App\Traits\ThreadTrait;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class ThreadListener
{
    use ThreadTrait;

    public function subscribe(Dispatcher $events)
    {
        // 发布帖子
        $events->listen(Saving::class, [$this, 'categorizeThread']);
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
    }

    /**
     * 分类主题
     *
     * @param Saving $event
     * @throws CategoryNotFoundException
     */
    public function categorizeThread(Saving $event)
    {
        $categoryId = Arr::get($event->data, 'relationships.category.data.id');

        if ($categoryId && $event->thread->category_id != $categoryId) {
            // 如果接收到可用的分类，则设置分类
            if ($categoryId = Category::where('id', $categoryId)->value('id')) {
                $event->thread->category_id = $categoryId;
            }

            // 如果没有分类，则抛出异常
            if (! $categoryId) {
                throw new CategoryNotFoundException;
            }
        }
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
     * 主题发布后 增加分类主题数量
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
     * @throws \App\Exceptions\ThreadException
     */
    public function whenThreadWasApproved(ThreadWasApproved $event)
    {
        $this->action($event->thread, $event->thread->is_approved, $action);

        OperationLog::writeLog($event->actor, $event->thread, $action, $event->data['message']);
    }

    /**
     * 隐藏主题时，记录操作
     *
     * @param Hidden $event
     * @throws \App\Exceptions\ThreadException
     */
    public function whenThreadWasHidden(Hidden $event)
    {
        $this->action($event->thread, 'hide', $action);

        OperationLog::writeLog($event->actor, $event->thread, $action, $event->data['message']);
    }

    /**
     * 删除主题时，删除主题下所有回复
     *
     * @param Deleted $event
     */
    public function whenThreadWasDeleted(Deleted $event)
    {
        Post::where('thread_id', $event->thread->id)->forceDelete();
    }
}
