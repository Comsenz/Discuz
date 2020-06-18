<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Topic;

use App\Api\Serializer\TopicSerializer;
use App\Commands\Topic\DeleteTopic;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchDeleteTopicController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = TopicSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @inheritDoc
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertAdmin($actor);

        $ids = explode(',', Arr::get($request->getQueryParams(), 'ids'));
        $idsCollect = collect($ids);

        $result = ['data' => [], 'meta' => []];
        $idsCollect->each(function ($id) use ($actor, &$result) {
            try {
                $result['data'][] = $this->bus->dispatch(
                    new DeleteTopic($id, $actor)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
            }
        });

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
