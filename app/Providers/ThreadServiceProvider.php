<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadServiceProvider.php xxx 2019-10-18 13:28:00 LiuDongdong $
 */

namespace App\Providers;

use App\Listeners\Thread\ThreadListener;
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
    }
}
