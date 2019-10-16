<?php

namespace Discuz\Setting;

use App\Settings\SettingsRepository;
use Discuz\Contracts\Setting\SettingRepository;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(SettingRepository::class, function($app) {
            return new SettingsRepository($app->make('cache'));
        });
    }
}
