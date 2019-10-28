<?php


namespace App\Settings;


use Discuz\Contracts\Setting\SettingsRepository as ContractsSettingsRepository;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{

    public function register() {
        $this->app->singleton(ContractsSettingsRepository::class, function($app) {
            return new SettingsRepository($app['cache']);
        });
    }
}
