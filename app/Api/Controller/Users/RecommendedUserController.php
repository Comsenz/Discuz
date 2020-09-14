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

use App\Api\Serializer\UserSerializer;
use App\Models\User;
use App\Repositories\UserFollowRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class RecommendedUserController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = UserSerializer::class;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var UserRepository
     */
    public $users;

    public $cache;

    public $include = ['groups'];

    /**
     * @param UrlGenerator $url
     * @param UserRepository $users
     * @param Cache $cache
     */
    public function __construct(UrlGenerator $url, UserRepository $users, Cache $cache)
    {
        $this->users = $users;
        $this->url = $url;
        $this->cache = $cache;
    }

    /**
     * 我的关注
     * {@inheritdoc}
     * @throws InvalidParameterException|InvalidArgumentException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $include = $this->extractInclude($request);
        $limit = Arr::get($request->getQueryParams(), 'limit');

        $cacheKey = 'users_recommendedUser';
        $cacheData = $this->cache->get($cacheKey);
        if ($cacheData < $limit) {
            $cacheData = [];
        }

        $users = $this->search($cacheData, $limit);

        // 加载关联
        $users->loadMissing($include);

        return $users;
    }

    /**
     * @param $cacheData
     * @param null $limit
     * @return Collection
     */
    public function search($cacheData, $limit)
    {
        $query = $this->users->query()->select('users.*')
        ->where('status', 0)
        ->whereBetween('login_at', [Carbon::now(), Carbon::parse('-30 days')])
        ->orderBy('thread_count', 'desc')
        ->orderBy('login_at', 'desc');
        // cache
        if ($cacheData) {
            $query->whereIn('id', $cacheData);
        }

        $query->take($limit);

        return $query->get();
    }
}
