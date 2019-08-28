<?php

namespace Discuz\Web;

use Discuz\Http\Middleware\DispatchRoute;
use Illuminate\Support\ServiceProvider;
use Discuz\Http\RouteCollection;
use Discuz\Http\RouteHandlerFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function Zend\Stratigility\middleware;
use Zend\Stratigility\MiddlewarePipe;
use function Zend\Stratigility\path;

class WebServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(RouteCollection::class, function() {
            return new RouteCollection;
        });

        $this->app->singleton(RouteHandlerFactory::class, function($app) {
            return new RouteHandlerFactory($app);
        });

        $this->app->singleton('discuz.web.middleware', function() {
           $pipe = new MiddlewarePipe;
            //todo

           return $pipe;
        });

        //保证路由中间件最后执行
        $this->app->afterResolving('discuz.web.middleware', function(MiddlewarePipe $pipe) {
            $pipe->pipe($this->app->get(DispatchRoute::class));
        });
    }

    public function boot() {
        $this->populateRoutes($this->app->get(RouteCollection::class));
    }

    /**
     * Populate the API routes.
     *
     * @param RouteCollection $routes
     */
    protected function populateRoutes(RouteCollection $route)
    {
        require $this->app->basePath('routes/web.php');
    }

}
