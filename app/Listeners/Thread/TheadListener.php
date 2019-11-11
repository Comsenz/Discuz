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
use App\Events\Thread\Saving;
use App\Models\Post;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;

class ThreadListener
{
    public function subscribe(Dispatcher $events)
    {
        // 发布帖子
        $events->listen(Created::class, [$this, 'whenPostWasCreated']);

        // 删除主题
        $events->listen(Deleted::class, [$this, 'whenThreadWasDeleted']);

        // 收藏主题
        $events->listen(Serializing::class, AddThreadFavoriteAttribute::class);
        $events->listen(Saving::class, SaveFavoriteToDatabase::class);
    }

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
     * 删除主题时，删除主题下所有回复
     *
     * @param Deleted $event
     */
    public function whenThreadWasDeleted(Deleted $event)
    {
        Post::where('thread_id', $event->thread->id)->forceDelete();
    }
}
