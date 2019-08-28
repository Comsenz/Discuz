<?php


namespace Discuz\Foundation;


use Discuz\Http\Middleware\DispatchRoute;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Stratigility\MiddlewarePipe;

class SiteApp implements AppInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getRequestHandler() : RequestHandlerInterface
    {
        // TODO: Implement getRequestHandler() method.

        $pipe = new MiddlewarePipe();


        $pipe->pipe($this->app->get('discuz.web.middleware'));
        return $pipe;
    }
}
