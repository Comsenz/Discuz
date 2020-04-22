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
use App\User\AvatarUploader;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when([AvatarUploader::class, UserWechatObserver::class])
            ->needs(Filesystem::class)
            ->give(function (Application $app) {
                return $app->make(Factory::class)->disk('avatar');
            });
    }

    public function boot()
    {
        $events = $this->app->make('events');

        // 订阅事件
        $events->subscribe(UserListener::class);

        //添加Observer
        User::observe(UserObserver::class);
        UserWechat::observe(UserWechatObserver::class);
    }
}
