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

namespace App\Formatter;

use Discuz\Cache\CacheManager;
use Discuz\Foundation\AbstractServiceProvider;
use Discuz\Foundation\Application;
use Discuz\Http\UrlGenerator;

class FormatterServiceProvider extends AbstractServiceProvider
{
    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * {@inheritdoc}
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->url = $app->make(UrlGenerator::class);
        $this->cache = $app->make('cache');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton(Formatter::class, function () {
            return new Formatter(
                $this->url,
                $this->cache,
                $this->app
            );
        });

        $this->app->singleton(DialogMessageFormatter::class, function () {
            return new DialogMessageFormatter(
                $this->url,
                $this->cache,
                $this->app
            );
        });
    }
}
