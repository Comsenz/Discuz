<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Listeners\User\UserListener;
use App\Models\User;
use App\Models\UserWechat;
use App\Observer\UserObserver;
use App\Observer\UserWechatObserver;
use Discuz\Foundation\AbstractServiceProvider;

class UserServiceProvider extends AbstractServiceProvider
{
    public function boot()
    {
        $events = $this->app->make('events');

        $events->subscribe(UserListener::class);

        User::observe(UserObserver::class);
        UserWechat::observe(UserWechatObserver::class);
    }
}
