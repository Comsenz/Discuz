<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\UserRefreshCount;
use Illuminate\Contracts\Events\Dispatcher;

class UserListener
{
    public function subscribe(Dispatcher $events)
    {
        // 刷新分类数
        $events->listen(UserRefreshCount::class, [$this, 'refreshCount']);
    }

    public function refreshCount(UserRefreshCount $event)
    {
        $event->user->refreshThreadCount();

        $event->user->save();
    }
}
