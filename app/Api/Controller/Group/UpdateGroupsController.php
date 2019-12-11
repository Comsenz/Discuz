<?php

namespace App\Api\Controller\Group;


use App\Api\Serializer\GroupSerializer;
use App\Commands\Group\UpdateGroup;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class UpdateGroupsController extends AbstractListController
{
    public $serializer = GroupSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $multipleData = Arr::get($request->getParsedBody(), 'data', []);

        $list = collect();
        foreach($multipleData as $data) {
            $list->push(
                $this->bus->dispatch(
                    new UpdateGroup(
                        Arr::get($data, 'attributes.id'),
                        $request->getAttribute('actor'),
                        $data
                    )
                )
            );
        }

        return $list;
    }
}
