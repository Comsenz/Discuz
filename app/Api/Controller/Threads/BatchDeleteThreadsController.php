<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Commands\Thread\BatchDeleteThreads;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchDeleteThreadsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = ThreadSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $ids = explode(',', Arr::get($request->getQueryParams(), 'ids'));
        $actor = $request->getAttribute('actor');

        $result = $this->bus->dispatch(
            new BatchDeleteThreads($ids, $actor)
        );

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
