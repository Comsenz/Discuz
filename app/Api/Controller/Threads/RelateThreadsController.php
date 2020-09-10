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
use App\Models\Order;
use App\Models\Post;
use App\Models\PostUser;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Repositories\TopicRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

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
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $threadId = (int)Arr::get($request->getQueryParams(), 'id');
        $limit = Arr::get($request->getQueryParams(), 'limit');
        $include = $this->extractInclude($request);

        $thread = $this->threads->findOrFail($threadId);
        $topicIdArr = $thread->topic()->pluck('id');

        //话题主题
        $query = $this->threads->query()
            ->select('threads.*')
            ->join('thread_topic', 'threads.id', '=', 'thread_topic.thread_id')
            ->where('is_approved', true)
            ->where('id', '<>', $threadId)
            ->whereNotNull('user_id')
            ->whereNull('deleted_at')
            ->whereIn('thread_topic.topic_id', $topicIdArr)
            ->orderBy('threads.view_count', 'desc');
        $threads = $query->take($limit)->get();
        $threadsIdArr = array_merge($threads->pluck('id')->toArray(), [$threadId]);

        if ($threads->count() < $limit) {
            //分类主题
            $query = $this->threads->query()->select('threads.*')
                ->where('category_id', $thread->category_id)
                ->where('is_approved', true)
                ->whereNotIn('id', $threadsIdArr)
                ->whereNotNull('user_id')
                ->whereNull('deleted_at')
                ->orderBy('threads.view_count', 'desc');
            $categoryThreads = $query->take($limit-$threads->count())->get();
            $threads = $threads->merge($categoryThreads);
            $threadsIdArr = array_merge($threads->pluck('id')->toArray(), [$threadId]);
        }
        if ($threads->count() < $limit) {
            //全站主题
            $query = $this->threads->query()->select('threads.*')
                ->whereNotNull('user_id')
                ->whereNull('deleted_at')
                ->whereNotIn('id', $threadsIdArr)
                ->orderBy('threads.view_count', 'desc');
            $categoryThreads = $query->take($limit-$threads->count())->get();
            $threads = $threads->merge($categoryThreads);
        }

        // 加载关联
        $threads->loadMissing($include);

        return $threads;
    }
}
