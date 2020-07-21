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

use App\Formatter\Formatter;
use App\Formatter\MarkdownFormatter;
use App\Listeners\Post\PostAttachment;
use App\Listeners\Post\PostListener;
use App\Models\Post;
use App\Policies\PostPolicy;
use Discuz\Foundation\AbstractServiceProvider;

class PostServiceProvider extends AbstractServiceProvider
{
    /**
     * 注册服务.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * 引导服务.
     *
     * @return void
     */
    public function boot()
    {
        Post::setFormatter($this->app->make(Formatter::class));
        Post::setMarkdownFormatter($this->app->make(MarkdownFormatter::class));

        // 事件处理类
        $events = $this->app->make('events');

        // 订阅事件
        $events->subscribe(PostListener::class);
        $events->subscribe(PostPolicy::class);

        $events->subscribe(PostAttachment::class);
    }
}
