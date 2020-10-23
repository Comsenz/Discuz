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

namespace App\Listeners;

use App\Api\Middleware\ClearSessionMiddleware;
use App\Api\Middleware\FakeHttpMethods;
use App\Api\Middleware\OperationLogMiddleware;
use Discuz\Api\Events\ConfigMiddleware;
use Discuz\Foundation\Application;
use App\Api\Middleware\CheckPaidUserGroupMiddleware;

class AddApiMiddleware
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(ConfigMiddleware $event)
    {
        $event->pipe->pipe($this->app->make(ClearSessionMiddleware::class));
        $event->pipe->pipe($this->app->make(FakeHttpMethods::class));
        $event->pipe->pipe($this->app->make(OperationLogMiddleware::class));
        $event->pipe->pipe($this->app->make(CheckPaidUserGroupMiddleware::class));
    }
}
