<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: AuthenticateWithSession.php 28830 2019-10-11 15:04 chenkeke $
 */

namespace App\Http\Middleware;

use Illuminate\Contracts\Session\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class AuthenticateWithSession implements Middleware
{
    public function process(Request $request, Handler $handler): Response
    {
        $session = $request->getAttribute('session');

        $actor = $this->getActor($session);

        $actor->setSession($session);

        $request = $request->withAttribute('actor', $actor);

        return $handler->handle($request);
    }

    private function getActor(Session $session)
    {
        $actor = User::find($session->get('user_id')) ?: new Guest;

        if ($actor->exists) {
            $actor->updateLastSeen()->save();
        }

        return $actor;
    }
}