<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: TheadListener.php xxx 2019-10-18 12:14:00 LiuDongdong $
 */

namespace App\Listeners;

use App\Events\Post\Created;
use Illuminate\Contracts\Events\Dispatcher;

class TheadListener
{
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Created::class, [$this, 'whenPostWasCreated']);
    }

    public function whenPostWasCreated(Created $event)
    {
        $thread = $event->post->thread;

        if ($thread && $thread->exists) {
            $thread->refreshReplyCount();
            $thread->refreshLastPost();
            $thread->save();
        }
    }
}
