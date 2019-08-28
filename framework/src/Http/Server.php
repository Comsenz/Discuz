<?php

namespace Discuz\Http;

use Discuz\Foundation\Application;
use Discuz\Foundation\SiteInterface;
use Discuz\Http\Middleware\DispatchRoute;
use Throwable;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\HttpHandlerRunner\RequestHandlerRunner;
use function Zend\Stratigility\middleware;
use Zend\Stratigility\Middleware\ErrorResponseGenerator;
use Zend\Stratigility\MiddlewarePipe;
use function Zend\Stratigility\path;

class Server
{
    protected $site;

    public function __construct(SiteInterface $site)
    {
        $this->site = $site;
    }

    public function listen() {

        $app = $this->site->bootApp();

        $runner = new RequestHandlerRunner(
            $app->getRequestHandler(),
            new SapiEmitter,
            [ServerRequestFactory::class, 'fromGlobals'],
            function (Throwable $e) {
                $generator = new ErrorResponseGenerator;
                return $generator($e, new ServerRequest, new Response);
            }
        );

        $runner->run();
    }
}
