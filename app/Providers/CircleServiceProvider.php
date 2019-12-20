<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CircleServiceProvider.php 28830 2019-09-26 14:16 chenkeke $
 */

namespace App\Providers;

use Discuz\Repository\RepositoryInterface;
use Discuz\Foundation\AbstractServiceProvider;
use App\Policies\CirclePolicy;

class CircleServiceProvider extends AbstractServiceProvider
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
        $events->subscribe(CirclePolicy::class);

        // 监听事件
        // $events->listen(
        //     Renamed::class, DiscussionRenamedLogger::class
        // );

    }

}