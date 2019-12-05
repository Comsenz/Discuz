<?php

namespace App\Api\Controller\Group;


use App\Commands\Group\DeleteGroup;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class BatchDeleteGroupController extends AbstractResourceController
{
    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        dd(123448);
//        return $this->bus->dispatch(new DeleteGroup(Arr::get($request->getQueryParams(), 'id'), $request->getParsedBody(), $request->getAttribute('actor')));
    }
}
