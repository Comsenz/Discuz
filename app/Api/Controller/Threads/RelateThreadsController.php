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

use App\Api\Serializer\ThreadSerializer;
use App\Repositories\ThreadRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class RelateThreadsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = ThreadSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'firstPost',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'threadVideo',
        'category',
        'lastPostedUser',
        'user.groups',
        'firstPost.images',
        'firstPost.attachments',
        'firstPost.likedUsers',
    ];

    /**
     * @var ThreadRepository
     */
    protected $threads;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param ThreadRepository $threads
     * @param UrlGenerator $url
     */
    public function __construct(ThreadRepository $threads, UrlGenerator $url)
    {
        $this->threads = $threads;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $threadId = (int)Arr::get($request->getQueryParams(), 'id');
        $limit = Arr::get($request->getQueryParams(), 'limit');
        $include = $this->extractInclude($request);

        $thread = $this->threads->findOrFail($threadId);
        $topicIdArr = $thread->topic()->pluck('id');

        //话题主题
        $threads = $this->search($limit, [$threadId], null, $topicIdArr);

        if ($threads->count() < $limit) {
            //分类主题
            $excludeThreads = array_merge($threads->pluck('id')->toArray(), [$threadId]);
            $categoryThreads = $this->search($limit-$threads->count(), $excludeThreads, $thread->category_id);
            $threads = $threads->merge($categoryThreads);
        }
        if ($threads->count() < $limit) {
            //全站主题
            $excludeThreads = array_merge($threads->pluck('id')->toArray(), [$threadId]);
            $totalThreads = $this->search($limit-$threads->count(), $excludeThreads);
            $threads = $threads->merge($totalThreads);
        }

        // 加载关联
        $threads->loadMissing($include);

        return $threads;
    }

    /**
     * @param $limit
     * @param $excludeThreads
     * @param null $category_id
     * @param null $topicIdArr
     * @return Builder[]|Collection
     */
    private function search($limit, $excludeThreads, $category_id = null, $topicIdArr = null)
    {
        $query = $this->threads->query()->select('threads.*')
            ->whereNotNull('user_id')
            ->whereNull('deleted_at')
            ->where('is_approved', true)
            ->whereNotIn('id', $excludeThreads)
            ->orderBy('threads.view_count', 'desc');
        // 分类
        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        // 话题
        if ($topicIdArr) {
            $query->join('thread_topic', 'threads.id', '=', 'thread_topic.thread_id')
                ->whereIn('thread_topic.topic_id', $topicIdArr);
        }

        return $query->take($limit)->get();
    }
}
