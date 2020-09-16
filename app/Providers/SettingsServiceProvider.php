<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Providers;

use App\Api\Serializer\AttachmentSerializer;
use App\Commands\Attachment\AttachmentUploader;
use App\Listeners\Setting\SettingListener;
use App\Models\Setting;
use App\Observer\UserWechatObserver;
use App\Settings\SettingsRepository;
use App\User\AvatarUploader;
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

        // 必须设置完加解密函数之后才能调用
        if ($this->app->isInstall()) {
            $settings = $this->app->make(ContractsSettingsRepository::class);
            $qcloud = $settings->tag('qcloud');

            $attachmentDisk = 'attachment';
            $avatarDisk = 'avatar';

            if (Arr::get($qcloud, 'qcloud_cos', false)) {
                $attachmentDisk = 'attachment_cos';
                $avatarDisk = 'avatar_cos';
            }

            $this->app->when([
                AttachmentUploader::class,
                AttachmentSerializer::class,
            ])
            ->needs(ContractsFilesystem::class)
            ->give(function (Application $app) use ($attachmentDisk) {
                return $app->make(Factory::class)->disk($attachmentDisk);
            });

            $this->app->when([
                AvatarUploader::class,
                UserWechatObserver::class,
            ])
            ->needs(ContractsFilesystem::class)
            ->give(function (Application $app) use ($avatarDisk) {
                return $app->make(Factory::class)->disk($avatarDisk);
            });
        }

        $events = $this->app->make('events');

        $events->subscribe(SettingListener::class);
    }
}
