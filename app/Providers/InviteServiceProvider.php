<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: InviteServiceProvider.php 28830 2019-10-12 15:59 chenkeke $
 */

namespace App\Providers;


use App\Policies\InvitePolicy;
use Discuz\Foundation\AbstractServiceProvider;

class InviteServiceProvider extends AbstractServiceProvider
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
        /**
         * 事件处理类
         */
        $events = $this->app->make('events');

        // 订阅事件
        $events->subscribe(InvitePolicy::class);

    }

}