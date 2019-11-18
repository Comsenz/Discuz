<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchUpdateThreadsController.php xxx 2019-10-21 14:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Commands\Thread\BatchEditThreads;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchUpdateThreadsController extends AbstractListController
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
        $data = $request->getParsedBody()->get('data', []);

        $result = $this->bus->dispatch(
            new BatchEditThreads($ids, $actor, $data)
        );

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
