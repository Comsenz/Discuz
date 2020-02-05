<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Api\Serializer\AttachmentSerializer;
use App\Models\Setting;
use App\Settings\SettingsRepository;
use App\Tools\AttachmentUploadTool;
use App\Tools\ImageUploadTool;
use Discuz\Contracts\Setting\SettingsRepository as ContractsSettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem as ContractsFilesystem;
use Illuminate\Support\Arr;
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

        //必须设置完加解密函数之后才能调用
        $settings = $this->app->make(ContractsSettingsRepository::class);
        $qcloud = $settings->tag('qcloud');

        if(Arr::get($qcloud, 'qcloud_cos', false)) {
            $this->app->when([AttachmentUploadTool::class, ImageUploadTool::class, AttachmentSerializer::class])->needs(ContractsFilesystem::class)->give(function (Application $app) {
                return $app->make(Factory::class)->disk('attachment');
            });
        }
    }
}
