<?php


namespace App\Providers;

use App\Listeners\Credit\IncreaseCreditScoreListener;
use App\Listeners\Credit\IncreaseCreditScoreSubscribe;
use Discuz\Foundation\AbstractServiceProvider;

class IncreaseCreditScoreServiceProviders extends AbstractServiceProvider
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
        $events->subscribe(IncreaseCreditScoreSubscribe::class);
    }

}
