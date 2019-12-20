<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Settings\SettingsRepository;
use Discuz\Contracts\Setting\SettingsRepository as ContractsSettingsRepository;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ContractsSettingsRepository::class, function ($app) {
            return new SettingsRepository($app['cache']);
        });
    }
}
