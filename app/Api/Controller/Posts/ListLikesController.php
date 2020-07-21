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

namespace App\Api\Controller\Posts;

use Discuz\Auth\AssertPermissionTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListLikesController extends ListPostsController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $query = $actor->likedPosts();

        $this->postCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit)->orderBy('post_user.created_at', 'desc');

        $document->addPaginationLinks(
            $this->url->route('threads.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->postCount
        );

        $document->setMeta([
            'postCount' => $this->postCount,
            'pageCount' => ceil($this->postCount / $limit),
        ]);

        return $query->get()->load($include);
    }
}
