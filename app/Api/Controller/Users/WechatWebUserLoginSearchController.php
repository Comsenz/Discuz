<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Commands\Users\WebUserSearch;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tobscure\JsonApi\Document;

class WechatWebUserLoginSearchController implements RequestHandlerInterface
{

    protected $bus;

    public function __construct( Dispatcher $bus)
    {
        $this->bus = $bus;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $scene_str = $request->getParsedBody()->get('scene_str');
        return $this->bus->dispatch(
            new WebUserSearch($scene_str)
        );
    }
}
