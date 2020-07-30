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

namespace App\Api\Controller\Users;

use App\Api\Serializer\UserFollowSerializer;
use App\Models\User;
use App\Repositories\UserFollowRepository;
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
     * @var UserRepository
     */
    public $user;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['fromUser', 'toUser', 'fromUser.groups', 'toUser.groups'];

    /* The relationships that are included by default.
     *
     * @var array
     */
    public $include = [];

    /**
     * @param UserFollowRepository $userFollow
     * @param UrlGenerator $url
     * @param UserRepository $user
     */
    public function __construct(UserFollowRepository $userFollow, UrlGenerator $url, UserRepository $user)
    {
        $this->userFollow = $userFollow;
        $this->user = $user;
        $this->url = $url;
    }

    /**
     * 我的关注
     * {@inheritdoc}
     * @throws InvalidParameterException
     * @throws NotAuthenticatedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);
        //没传用户ID需要登陆
        if (!Arr::get($filter, 'user_id')) {
            $this->assertRegistered($actor);
        }

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $userFollow = $this->search($actor, $filter, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('follow.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->userFollowCount
        );

        $userFollow->loadMissing($include);

        $document->setMeta([
            'total' => $this->userFollowCount,
            'pageCount' => ceil($this->userFollowCount / $limit),
        ]);

        return $userFollow;
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
        $user = '';
        $query = $this->userFollow->query();

        $type = (int) Arr::get($filter, 'type', 1);
        $username = Arr::get($filter, 'username');
        if ($user_id = (int) Arr::get($filter, 'user_id')) {
            $user = $this->user->findOrFail($user_id);
        }
        $user_id = $user ? $user->id : $actor->id;

        if ($type == 1) {
            //我的关注
            $query->where('from_user_id', $user_id)->with('toUser');
            $join_field = 'to_user_id';
        } elseif ($type == 2) {
            //我的粉丝
            $query->where('to_user_id', $user_id)->with('fromUser');
            $join_field = 'from_user_id';
        }

        if ($username) {
            $query->join('users', 'users.id', '=', 'user_follow.'.$join_field)
                ->where(function ($query) use ($username) {
                    $query->where('users.username', 'like', "%{$username}%");
                });
        }

        $this->userFollowCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
