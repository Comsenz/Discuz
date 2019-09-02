<?php

namespace App\Http\Controller;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\HtmlResponse;

class IndexController implements RequestHandlerInterface {
    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
//        throw new \Exception('asldjflsdf', 333);

        $users = User::all();

        return new HtmlResponse('BAR!'.(microtime(true)-DISCUZ_START));
    }
}
