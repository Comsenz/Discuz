<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Events\Users\Saving;
use App\Listeners\User\AddDefaultGroup;
use App\Listeners\User\UserListener;
use App\User\AvatarUploader;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when(AvatarUploader::class)
            ->needs(Filesystem::class)
            ->give(function (Application $app) {
                return $app->make(Factory::class)->disk('avatar');
            });
    }

    public function boot()
    {
        $events = $this->app->make('events');

        $events->listen(Saving::class, AddDefaultGroup::class);

        // 订阅事件
        $events->subscribe(UserListener::class);
    }
}
