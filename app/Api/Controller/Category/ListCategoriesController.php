<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
