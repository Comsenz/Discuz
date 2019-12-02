<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadListener.php xxx 2019-10-18 12:14:00 LiuDongdong $
 */

namespace App\Listeners\Thread;

use App\Events\Post\Created;
use App\Events\Thread\Deleted;
use App\Events\Thread\Hidden;
use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use App\Models\OperationLog;
use App\Models\Post;
use Carbon\Carbon;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class ThreadListener
{
    public function subscribe(Dispatcher $events)
    {
        // 发布帖子
        $events->listen(Saving::class, [$this, 'categorizeThread']);
        $events->listen(Created::class, [$this, 'whenPostWasCreated']);

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

        if (! $categoryId = Category::where('id', $categoryId)->value('id')) {
            throw new CategoryNotFoundException;
        }

        $event->thread->category_id = $categoryId;
    }

    /**
     * 发布首帖时，更新主题回复数，最后回复 ID
     *
     * @param Created $event
     */
    public function whenPostWasCreated(Created $event)
    {
        $thread = $event->post->thread;

        if ($thread && $thread->exists) {
            $thread->refreshPostCount();
            $thread->refreshLastPost();
            $thread->save();
        }
    }

    /**
     * 审核主题时，记录操作
     *
     * @param ThreadWasApproved $event
     */
    public function whenThreadWasApproved(ThreadWasApproved $event)
    {
        if ($event->thread->is_approved == 1) {
            $action = 'approve';
        } elseif ($event->thread->is_approved == 2) {
            $action = 'ignore';
        } else {
            $action = 'disapprove';
        }

        $log = new OperationLog;
        $log->action = $action;
        $log->message = $event->data['message'];
        $log->created_at = Carbon::now();
        $event->thread->logs()->save($log);
    }

    /**
     * 隐藏主题时，记录操作
     *
     * @param Hidden $event
     */
    public function whenThreadWasHidden(Hidden $event)
    {
        $log = new OperationLog;
        $log->action = 'hide';
        $log->message = $event->data['message'];
        $log->created_at = Carbon::now();
        $event->thread->logs()->save($log);
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
