<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchUpdateThreadsController.php xxx 2019-10-21 14:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Commands\Thread\BatchUpdateThreads;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchUpdateThreadsController extends AbstractResourceController
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
        $actor = $request->getAttribute('actor');
        $data = $request->getParsedBody()->get('data', []);
        $ids = (array) Arr::get($data, 'attributes.ids', []);

        $this->bus->dispatch(
            new BatchUpdateThreads($ids, $actor, $data)
        );

        return null;
    }
}
