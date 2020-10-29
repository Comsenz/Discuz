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

namespace App\Api\Controller\Topic;

use App\Api\Serializer\TopicSerializer;
use App\Models\Category;
use App\Models\ThreadTopic;
use App\Models\Topic;
use App\Repositories\TopicRepository;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ListTopicController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = TopicSerializer::class;

    /**
     * @var TopicRepository
     */
    protected $topics;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    public $topicCount;

    /**
     * @var UserRepository
     */
    public $users;

    public $sortFields = ['threadCount', 'viewCount', 'createdAt', 'recommended', 'recommendedAt'];

    public $sort = ['createdAt' => 'desc'];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['user', 'lastThread', 'lastThread.firstPost', 'lastThread.firstPost.images'];

    /* The relationships that are included by default.
     *
     * @var array
     */
    public $include = [];

    /**
     * @param TopicRepository $topics
     * @param UrlGenerator $url
     * @param UserRepository $user
     */
    public function __construct(TopicRepository $topics, UrlGenerator $url, UserRepository $user)
    {
        $this->topics = $topics;
        $this->users = $user;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);

        $actor = $request->getAttribute('actor');

        $topics = $this->search($filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('topics.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->topicCount
        );

        if (in_array('lastThread', $include)) {
            $topicIds = $topics->pluck('id');
            $threadTopic = ThreadTopic::query()
                ->selectRaw(' `topic_id`, MAX(`thread_id`) as thread_id')
                ->join('threads', 'id', '=', 'thread_id')
                ->whereNotIn('category_id', Category::getIdsWhereCannot($actor, 'viewThreads'))
                ->whereNull('deleted_at')
                ->whereNotNull('threads.user_id')
                ->whereIn('topic_id', $topicIds)
                ->groupBy('topic_id')
                ->get();

            $threadIds = $threadTopic->pluck('thread_id');
            $topics->load([
                'lastThread' => function ($query) use ($threadIds) {
                    $query->whereIn('thread_id', $threadIds);
                }
            ]);
            $topics->each(function (Topic $topic) {
                //话题包含多个 $threadIds 只保留最大的
                if ($topic->getRelation('lastThread')->count() > 1) {
                    $topic->setRelation('lastThread', $topic->getRelation('lastThread')->sortByDesc('id')->take(1));
                }
            });
        }
        $topics->loadMissing($include);



        $document->setMeta([
            'total' => $this->topicCount,
            'pageCount' => ceil($this->topicCount / $limit),
        ]);

        return $topics;
    }

    /**
     * @param array $filter
     * @param $sort
     * @param null $limit
     * @param int $offset
     * @return Collection
     */
    public function search($filter, $sort, $limit = null, $offset = 0)
    {
        $query = $this->topics->query()->select('topics.*');

        if ($username = trim(Arr::get($filter, 'username'))) {
            $query->join('users', 'users.id', '=', 'topics.user_id')
                ->where('users.username', 'like', '%' . $username . '%');
        }

        if ($content = trim(Arr::get($filter, 'content'))) {
            $query->where('topics.content', 'like', '%' . $content . '%');
        }

        if ($createdAtBegin = Arr::get($filter, 'createdAtBegin')) {
            $query->where('topics.created_at', '>=', $createdAtBegin);
        }

        if ($createdAtEnd = Arr::get($filter, 'createdAtEnd')) {
            $query->where('topics.created_at', '<=', $createdAtEnd);
        }

        if ($threadCountBegin = Arr::get($filter, 'threadCountBegin')) {
            $query->where('topics.thread_count', '>=', $threadCountBegin);
        }

        if ($threadCountEnd = Arr::get($filter, 'threadCountEnd')) {
            $query->where('topics.thread_count', '<=', $threadCountEnd);
        }

        if ($viewCountBegin = Arr::get($filter, 'viewCountBegin')) {
            $query->where('topics.view_count', '>=', $viewCountBegin);
        }

        if ($viewCountEnd = Arr::get($filter, 'viewCountEnd')) {
            $query->where('topics.view_count', '<=', $viewCountEnd);
        }
        if (Arr::has($filter, 'recommended') && Arr::get($filter, 'recommended') != '') {
            $query->where('topics.recommended', (int)Arr::get($filter, 'recommended'));
        }

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        $this->topicCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
