<?php

namespace App\Api\Controller\Group;


use App\Api\Serializer\GroupSerializer;
use App\Commands\Group\UpdateGroup;
use App\Models\Thread;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class UpdateGroupController extends AbstractResourceController
{
    public $serializer = GroupSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        dd($request->getAttribute('actor'));
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
