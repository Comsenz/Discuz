<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Authentication.php 28830 2019-10-14 12:00 chenkeke $
 */

namespace App\Api\Middleware;

use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Authentication implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute('session');

        $actor = $this->getActor($session);

        $request = $request->withAttribute('actor', $actor);

        return $handler->handle($request);
    }

    private function getActor($session)
    {
        $actor = User::creation();

        return $actor;
    }
}