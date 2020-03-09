<?php


namespace App\Listeners;


use App\Api\Middleware\ClearSessionMiddleware;
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
    }
}
