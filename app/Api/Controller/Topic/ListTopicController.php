<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Topic;

use App\Api\Serializer\TopicSerializer;
use App\Models\Post;
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

    public $sortFields = ['threadCount', 'viewCount', 'createdAt'];

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

        $topics = $this->search($filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('topics.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->topicCount
        );


        $topicIds = $topics->pluck('id');
        $threadTopic = ThreadTopic::query()
            ->selectRaw(' `topic_id`, MAX(`thread_id`) as thread_id')
            ->whereIn('topic_id', $topicIds)
            ->groupBy('topic_id')
            ->get();
        $threadIds = $threadTopic->pluck('thread_id');
        $topics->load([
            'lastThread' => function ($query) use ($threadIds) {
                $query->whereIn('thread_id', $threadIds);
            }]);

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
        $query = $this->topics->query();
        if ($content = Arr::get($filter, 'content')) {
            $query->where('content', 'like', '%'.$content.'%');
        }

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        $this->topicCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
