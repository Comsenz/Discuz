<?php
declare(strict_types = 1);

namespace Discuz\Http\Middleware;

use Discuz\Http\Exception\MethodNotAllowedException;
use Discuz\Http\Exception\RouteNotFoundException;
use Discuz\Http\RouteCollection;
use Discuz\Http\RouteHandlerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use FastRoute\Dispatcher;
use function Zend\Stratigility\path;

class DispatchRoute implements MiddlewareInterface
{

    /**
     * @var RouteCollection
     */
    protected $routes;
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    protected $factory;

    /**
     * Create the middleware instance.
     *
     * @param RouteCollection $routes
     * @param RouteHandlerFactory $factory
     */
    public function __construct(RouteCollection $routes, RouteHandlerFactory $factory)
    {
        $this->routes = $routes;
        $this->factory = $factory;
    }

    /**
     * Dispatch the given request to our route collection.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath() ?: '/';
        $routeInfo = $this->getDispatcher()->dispatch($method, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException($uri);
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException($method);
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $parameters = $routeInfo[2];
                return $this->factory->toController($handler)($request, $parameters);
        }
    }
    protected function getDispatcher()
    {
        if (! isset($this->dispatcher)) {
            $this->dispatcher = new Dispatcher\GroupCountBased($this->routes->getRouteData());
        }
        return $this->dispatcher;
    }

    private function normalizePrefix(string $prefix) : string
    {
        $prefix = strlen($prefix) > 1 ? rtrim($prefix, '/') : $prefix;
        if (0 !== strpos($prefix, '/')) {
            $prefix = '/' . $prefix;
        }
        return $prefix;
    }
}
