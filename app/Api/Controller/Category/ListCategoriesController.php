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
        return Category::orderBy('sort')->get();
    }
}
