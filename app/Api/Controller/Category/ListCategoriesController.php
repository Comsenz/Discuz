<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Category;

use App\Api\Serializer\CategorySerializer;
use App\Models\Category;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListCategoriesController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = CategorySerializer::class;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);

        $query = Category::query();
        // 可发布主题的分类
        if ($actor->id && isset($filter['createThread']) && $filter['createThread']) {
            $query->whereNotIn('id', Category::getIdsWhereCannot($actor, 'createThread'));
        }
        // 仅返回可查看内容的分类
        return $query->whereNotIn('id', Category::getIdsWhereCannot($actor, 'viewThreads'))
            ->orderBy('sort')
            ->get();
    }
}
