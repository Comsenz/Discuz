<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners;

use App\Api\Middleware\ClearSessionMiddleware;
use App\Api\Middleware\FakeHttpMethods;
use App\Api\Middleware\OperationLogMiddleware;
use Discuz\Api\Events\ConfigMiddleware;
use Discuz\Foundation\Application;

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
    }
}
