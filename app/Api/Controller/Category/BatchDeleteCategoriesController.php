<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchDeleteCategoriesController.php xxx 2019-11-30 17:22:00 LiuDongdong $
 */

namespace App\Api\Controller\Category;

use App\Api\Serializer\CategorySerializer;
use App\Commands\Category\BatchDeleteCategories;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchDeleteCategoriesController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = CategorySerializer::class;

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
            new BatchDeleteCategories($ids, $actor)
        );

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
