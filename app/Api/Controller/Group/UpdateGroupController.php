<?php

namespace App\Api\Controller\Group;


use App\Commands\Group\UpdateGroup;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class UpdateGroupController extends AbstractResourceController
{
    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $res = $this->bus->dispatch(
            new UpdateGroup(
                Arr::get($request->getQueryParams(), 'id'),
                $request->getAttribute('actor'),
                $request->getParsedBody()->get('data', [])
            )
        );

        return $res;
    }
}
