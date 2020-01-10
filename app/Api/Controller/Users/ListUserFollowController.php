<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\UserFollowSerializer;
use App\Models\User;
use App\Repositories\UserFollowRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUserFollowController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = UserFollowSerializer::class;

    /**
     * @var UserFollowRepository
     */
    protected $userFollow;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    public $userFollowCount;

    /**
     * @param UserFollowRepository $userFollow
     * @param UrlGenerator $url
     */
    public function __construct(UserFollowRepository $userFollow, UrlGenerator $url)
    {
        $this->userFollow = $userFollow;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $filter = $this->extractFilter($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $userFollow = $this->search($actor, $filter, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('follow.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->userFollowCount
        );

        $document->setMeta([
            'total' => $this->userFollowCount,
            'size' => $limit,
        ]);

        return $userFollow;
    }

    /**
     * @param User $actor
     * @param array $filter
     * @param null $limit
     * @param int $offset
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(User $actor, $filter, $limit = null, $offset = 0)
    {
        $query = $this->userFollow->query()->whereVisibleTo($actor);

        $this->applyFilters($query, $filter, $actor);

        $this->userFollowCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
