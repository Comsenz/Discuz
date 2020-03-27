<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\WebUserSearch;
use App\MessageTemplate\Wechat\WechatRegisterMessage;
use App\Notifications\System;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WechatWebUserLoginSearchController extends AbstractResourceController
{

    protected $bus;

    public $serializer = TokenSerializer::class;

    public function __construct( Dispatcher $bus)
    {
        $this->bus = $bus;
    }


    protected function data(ServerRequestInterface $request, Document $document)
    {
        $scene_str = $request->getParsedBody()->get('scene_str');
        $this->bus->dispatch(
            new WebUserSearch($scene_str)
        );



    }
}
