<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Circle;

use Discuz\Api\Controller\AbstractListController;
use App\Api\Serializer\UserSerializer;
use App\Commands\Circle\CreateThread;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListCircleController extends AbstractListController
{
    public $serializer = UserSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
        $res = $this->bus->dispatch(
            new CreateThread('aaa', 'bb', ['cc'])
        );
        dd($res);
        return $res;
    }
}
