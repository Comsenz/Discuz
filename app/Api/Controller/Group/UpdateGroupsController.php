<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Group;

use App\Api\Serializer\GroupSerializer;
use App\Commands\Group\UpdateGroup;
use Discuz\Api\Controller\AbstractListController;
use Exception;
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

        $list = collect($multipleData)->reduce(function ($carry, $item) use ($request) {
            $carry = $carry ? $carry : ['data' => [], 'meta' => []];
            try {
                $group = $this->bus->dispatch(
                    new UpdateGroup(
                        Arr::get($item, 'attributes.id'),
                        $request->getAttribute('actor'),
                        $item
                    )
                );
                $carry['data'][] = $group;
                return $carry;
            } catch (Exception $e) {
                $item['attributes']['message'] = $e->getMessage();
                $carry['meta'][] = Arr::get($item, 'attributes');
                return $carry;
            }
        });

        $document->setMeta($list['meta']);

        return $list['data'];
    }
}
