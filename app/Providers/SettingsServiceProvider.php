<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Models\Setting;
use App\Settings\SettingsRepository;
use Discuz\Contracts\Setting\SettingsRepository as ContractsSettingsRepository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ContractsSettingsRepository::class, function ($app) {
            return new SettingsRepository($app['cache']);
        });
    }

    public function boot()
    {
        Setting::setEncrypt($this->app->make(Encrypter::class));
    }
}
