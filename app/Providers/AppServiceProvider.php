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

use App\Models\Post;
use App\Models\SessionToken;
use App\Models\Thread;
use App\Observer\PostObserver;
use App\Observer\ThreadObserver;
use App\SpecialChar\SpecialChar;
use Discuz\Foundation\AbstractServiceProvider;
use Discuz\SpecialChar\SpecialCharServer;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Validation\Factory as Validator;
use Intervention\Image\ImageManager;

class AppServiceProvider extends AbstractServiceProvider implements DeferrableProvider
{
    /**
     * 注册服务
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SpecialCharServer::class, function ($app) {
            return new SpecialChar($app);
        });

        $this->app->singleton(ImageManager::class, function ($app) {
            if (extension_loaded('imagick')) {
                return new ImageManager(['driver' => 'imagick']);
            } else {
                return new ImageManager();
            }
        });
    }

    /**
     * 引导服务
     *
     * @param Validator $validator
     * @return void
     */
    public function boot(Validator $validator)
    {
        // 自定义验证规则
        $validator->extend('session_token', function ($attribute, $value, $parameters, $validator) {
            // 至少需要一个参数即 scope
            $validator->requireParameterCount(1, $parameters, 'session_token');

            $userId = isset($parameters[1]) ? $parameters[1] : null;

            return SessionToken::check($value, $parameters[0], $userId);
        });


        Thread::observe(ThreadObserver::class);
        Post::observe(PostObserver::class);
    }
}
