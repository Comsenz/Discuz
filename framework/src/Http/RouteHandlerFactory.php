<?php

/*
 * This file is part of Fine.
 *
 * (c) Leiyu <yleimm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Id: RouteHandlerFactory.php 2018/11/28 17:54
 */

namespace Discuz\Http;


use Closure;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class RouteHandlerFactory
{
    /**
     * @var Container
     */
    protected $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    public function toController($controller): Closure
    {
        return function (Request $request, array $routeParams) use ($controller) {
            $controller = $this->resolveController($controller);
            $request = $request->withQueryParams(array_merge($request->getQueryParams(), $routeParams));
            return $controller->handle($request);
        };
    }

    private function resolveController($controller): Handler
    {
        if (is_callable($controller)) {
            $controller = $this->container->call($controller);
        } else {
            $controller = $this->container->make($controller);
        }
        if (! $controller instanceof Handler) {
            throw new InvalidArgumentException('Controller must be an instance of '.Handler::class);
        }
        return $controller;
    }
}
