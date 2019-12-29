<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Category;

use App\Api\Serializer\CategorySerializer;
use App\Commands\Category\DeleteCategory;
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

        $result = ['data' => [], 'meta' => []];

        foreach ($ids as $id) {
            try {
                $result['data'][] = $this->bus->dispatch(
                    new DeleteCategory($id, $actor)
                );
            } catch (\Exception $e) {
                $result['meta'][] = $e->getMessage();
            }
        }

        $document->setMeta($result['meta']);

        return $result['data'];
    }
}
