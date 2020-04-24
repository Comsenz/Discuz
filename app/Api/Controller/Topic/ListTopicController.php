<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Topic;

use App\Api\Serializer\TopicSerializer;
use App\Models\User;
use App\Repositories\TopicRepository;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
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

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['user', 'threads'];

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
     * 我的关注
     * {@inheritdoc}
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $topic = $this->search($actor, $filter, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('topics.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->topicCount
        );

        $topic->loadMissing($include);

        $document->setMeta([
            'total' => $this->topicCount,
            'pageCount' => ceil($this->topicCount / $limit),
        ]);

        return $topic;
    }

    /**
     * @param User $actor
     * @param array $filter
     * @param null $limit
     * @param int $offset
     * @return Collection
     */
    public function search(User $actor, $filter, $limit = null, $offset = 0)
    {
        $join_field = '';
        $query = $this->topics->query();

        $this->topicCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
