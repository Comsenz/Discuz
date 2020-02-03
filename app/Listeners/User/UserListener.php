<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\UserFollowCount;
use App\Events\Users\UserRefreshCount;
use Illuminate\Contracts\Events\Dispatcher;

class UserListener
{
    public function subscribe(Dispatcher $events)
    {
        // 刷新分类数
        $events->listen(UserRefreshCount::class, [$this, 'refreshCount']);

        // 刷新用户关注数粉丝数
        $events->listen(UserFollowCount::class, [$this, 'refreshFollowCount']);
    }

    public function refreshCount(UserRefreshCount $event)
    {
        $event->user->refreshThreadCount();

        $event->user->save();
    }

    public function refreshFollowCount(UserFollowCount $event)
    {
        //关注人的 关注数
        $event->fromUser->refreshUserFollow();
        $event->fromUser->save();

        //被关注人的 粉丝数
        $event->toUser->refreshUserFans();
        $event->toUser->save();
    }
}
