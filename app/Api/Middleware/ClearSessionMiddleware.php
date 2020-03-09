<?php


namespace App\Api\Middleware;


use App\Models\SessionToken;
use Carbon\Carbon;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ClearSessionMiddleware implements MiddlewareInterface
{

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 清除过期 session_token
        SessionToken::where('expired_at', '<', Carbon::now())->delete();

        return $handler->handle($request);
    }
}
