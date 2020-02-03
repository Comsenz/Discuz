<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Listeners\Thread\ThreadListener;
use App\Policies\ThreadPolicy;
use Discuz\Foundation\AbstractServiceProvider;

class ThreadServiceProvider extends AbstractServiceProvider
{
    /**
     * 注册服务.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * 引导服务.
     *
     * @return void
     */
    public function boot()
    {
        // 事件处理类
        $events = $this->app->make('events');

        // 订阅事件
        $events->subscribe(ThreadListener::class);
        $events->subscribe(ThreadPolicy::class);
    }
}
