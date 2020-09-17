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
use App\Models\User;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Support\Collection;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ListCategoriesController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = CategorySerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['moderators'];

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return Collection
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        /** @var User $actor */
        $actor = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);
        $include = $this->extractInclude($request);

        $query = Category::query();

        // 可查看内容的分类
        $query->whereNotIn('id', Category::getIdsWhereCannot($actor, 'viewThreads'));

        // 可发布内容的分类
        if ($actor->exists && isset($filter['createThread']) && $filter['createThread']) {
            $query->whereNotIn('id', Category::getIdsWhereCannot($actor, 'createThread'));
        }

        $categories = $query->orderBy('sort')->get();

        // 分类版主
        if (in_array('moderators', $include)) {
            $users = User::query()->findMany(
                $categories->pluck('moderators')->flatten()->unique()
            );

            $categories->map(function (Category $category) use ($users) {
                $category->setRelation('moderatorUsers', $users->whereIn('id', $category->moderators));
            });

            // 因关系与字段重名，序列化时使用 moderatorUsers，为避免 loadMissing 异常，移除 moderators
            $include = array_diff($include, ['moderators']);
        }

        return $categories->loadMissing($include);
    }
}
