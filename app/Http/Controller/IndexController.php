<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Http\Controller;

use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IndexController implements RequestHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route = $request->getUri()->getPath();

        return DiscuzResponseFactory::FileResponse(
            public_path(Str::startsWith($route, '/admin') ? 'admin.html' : 'index.html')
        );
    }
}
