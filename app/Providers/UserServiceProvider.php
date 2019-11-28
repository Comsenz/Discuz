<?php


namespace App\Providers;


use App\User\AvatarUploader;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->when(AvatarUploader::class)
            ->needs(Filesystem::class)
            ->give(function(Application $app) {
                return $app->make(Factory::class)->disk('avatar');
            });
    }
}
