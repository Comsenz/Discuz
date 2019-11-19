<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchUpdatePostsController.php xxx 2019-10-31 11:21:00 LiuDongdong $
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
use App\Commands\Post\BatchEditPosts;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchUpdatePostsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

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
            new BatchEditPosts($ids, $actor, $data)
        );

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
