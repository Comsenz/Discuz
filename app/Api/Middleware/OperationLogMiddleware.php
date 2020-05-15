<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Middleware;

use App\Models\OperationLog;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OperationLogMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $actor = $request->getAttribute('actor');

        if (!$request->hasHeader(OperationLog::HEADER_NAME) || $actor->isGuest()) {
            return $handler->handle($request);
        }

        if (OperationLog::existsToType($request->getHeaderLine(OperationLog::HEADER_NAME), $num)) {
            // created
            OperationLog::store(
                $actor->id,
                $request->getRequestTarget(),
                $request->getMethod(),
                ip($request->getServerParams()),
                $request->getParsedBody()->toJson(),
                $num
            );
        }

        return $handler->handle($request);
    }
}
