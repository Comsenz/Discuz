<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Policies\UserWalletLogsPolicy;
use Discuz\Foundation\AbstractServiceProvider;

class UserWalletLogsServiceProvider extends AbstractServiceProvider
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
        $events->subscribe(UserWalletLogsPolicy::class);
    }
}
