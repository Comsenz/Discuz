<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Censor;

use App\Settings\SettingsRepository;
use Illuminate\Support\ServiceProvider;

class CensorServiceProvider extends ServiceProvider
{
    /**
     * 是否延时加载提供器。
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * 注册服务提供器。
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Censor::class, function ($app) {
            return new Censor($app->make(SettingsRepository::class));
        });
    }

    /**
     * 获取提供器提供的服务。
     *
     * @return array
     */
    public function provides()
    {
        return [Censor::class];
    }

    public function boot()
    {
    }
}
