<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Api\Serializer\AttachmentSerializer;
use App\Models\Setting;
use App\Observer\UserWechatObserver;
use App\Settings\SettingsRepository;
use App\Tools\AttachmentUploadTool;
use App\Tools\ImageUploadTool;
use App\User\AvatarUploader;
use Discuz\Contracts\Setting\SettingsRepository as ContractsSettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
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
        if ($this->app->isInstall()) {
            $settings = $this->app->make(ContractsSettingsRepository::class);
            $qcloud = $settings->tag('qcloud');

            $attachmentDisk = 'attachment';
            $avatarDisk = 'avatar';

            if (Arr::get($qcloud, 'qcloud_cos', false)) {
                $attachmentDisk = 'attachment_cos';
                $avatarDisk = 'avatar_cos';
            }

            $this->app->when([AttachmentUploadTool::class, ImageUploadTool::class, AttachmentSerializer::class])->needs(ContractsFilesystem::class)->give(function (Application $app) use($attachmentDisk) {
                return $app->make(Factory::class)->disk($attachmentDisk);
            });

            $this->app->when([AvatarUploader::class, UserWechatObserver::class])
                ->needs(Filesystem::class)
                ->give(function (Application $app) use($avatarDisk) {
                    return $app->make(Factory::class)->disk($avatarDisk);
                });
        }
    }
}
