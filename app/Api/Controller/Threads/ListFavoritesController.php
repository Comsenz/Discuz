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

namespace App\Api\Controller\Threads;

use App\Models\Order;
use App\Models\Post;
use App\Models\Thread;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListFavoritesController extends ListThreadsController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $query = $actor->favoriteThreads();

        $this->threadCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit)->orderBy('thread_user.created_at', 'desc');

        $threads = $query->get();

        $document->addPaginationLinks(
            $this->url->route('threads.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->threadCount
        );

        $document->setMeta([
            'threadCount' => $this->threadCount,
            'pageCount' => ceil($this->threadCount / $limit),
        ]);

        Thread::setStateUser($actor, $threads);
        Post::setStateUser($actor);

        // 特殊关联：最新三条回复
        if (in_array('lastThreePosts', $include)) {
            $threads = $this->loadLastThreePosts($threads);
        }

        // 特殊关联：点赞的人
        if (in_array('firstPost.likedUsers', $include)) {
            $likedLimit = Arr::get($filter, 'likedLimit', 10);
            $threads = $this->loadLikedUsers($threads, $likedLimit);
        }

        // 特殊关联：打赏的人
        if (in_array('rewardedUsers', $include)) {
            $rewardedLimit = Arr::get($filter, 'rewardedLimit', 10);
            $threads = $this->loadRewardedUsers($threads, $rewardedLimit, Order::ORDER_TYPE_REWARD);
        }

        // 加载其他关联
        $threads->loadMissing($include);

        return $threads;
    }
}
