<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Formatter\Formatter;
use App\Listeners\Post\PostListener;
use App\Models\Post;
use App\Policies\PostPolicy;
use Discuz\Foundation\AbstractServiceProvider;

class PostServiceProvider extends AbstractServiceProvider
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
        Post::setFormatter($this->app->make(Formatter::class));

        // 事件处理类
        $events = $this->app->make('events');

        // 订阅事件
        $events->subscribe(PostListener::class);
        $events->subscribe(PostPolicy::class);
    }
}
