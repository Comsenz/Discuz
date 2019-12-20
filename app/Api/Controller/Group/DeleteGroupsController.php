<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Group;

use App\Api\Serializer\InfoSerializer;
use App\Commands\Group\DeleteGroup;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class DeleteGroupsController extends AbstractListController
{
    public $serializer = InfoSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $multipleData = Arr::get($request->getParsedBody(), 'data.id', []);

        $list = collect();
        foreach ($multipleData as $id) {
            $list->push(
                $this->bus->dispatch(
                    new DeleteGroup(
                        $id,
                        $request->getAttribute('actor')
                    )
                )
            );
        }

        return $list;
    }
}
