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

use App\Models\Category;
use App\Models\Post;
use App\Models\Thread;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListLikesController extends ListThreadsController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        // 没有任何一个分类的查看权限时，判断是否有全局权限
        if (! Category::getIdsWhereCan($actor, 'viewThreads')) {
            $this->assertCan($actor, 'viewThreads');
        }

        $limit = $this->extractLimit($request);
        $filter = $this->extractFilter($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);

        $threads = $this->search($actor, $filter, $sort, $limit, $offset);

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

        // 加载其他关联
        $threads->loadMissing($include);

        return $threads;
    }

    public function search($actor, $filter, $sort, $limit = null, $offset = 0)
    {
        $userId = Arr::get($filter, 'user_id', '0');

        /** @var Builder $query */
        $query = $this->threads->query()->whereVisibleTo($actor);

        $query = $query->select('threads.*')
            ->join('posts', 'threads.id', '=', 'posts.thread_id')
            ->join('post_user', 'posts.id', '=', 'post_user.post_id')
            ->where('post_user.user_id', $userId)
            ->where('posts.is_first', true)
            ->where('threads.is_approved', Thread::APPROVED)
            ->whereNull('threads.deleted_at');

        $this->threadCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        $query->orderBy('post_user.created_at', 'desc');

        return $query->get();
    }
}
