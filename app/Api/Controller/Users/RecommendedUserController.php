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
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
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
    /**
     * 缓存数据倍数
     */
    const DATA_MULTIPLE = 10;

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
     * {@inheritdoc}
     * @throws InvalidParameterException|InvalidArgumentException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $include = $this->extractInclude($request);
        $limit = (int)Arr::get($request->getQueryParams(), 'limit');

        $cacheKey = 'users_recommendedUser';
        $cacheData = $this->cache->get($cacheKey);
        $dataLimit = $limit * self::DATA_MULTIPLE;
        if ($cacheData && count($cacheData) >= $dataLimit) {
            $cacheData = Arr::random($cacheData, $limit);
        } else {
            //缓存不存在、数据不够时 重新创建缓存，设置缓存数据池条数
            $cacheData = null;
        }

        $users = $this->search($dataLimit, $cacheData);

        if (!$cacheData) {
            if ($users->count() == $dataLimit) {
                $this->cache->put($cacheKey, $users->pluck('id')->toArray(), 360);
            }
            if ($users->count() >= $limit) {
                $users = $users->random($limit);
            }
        }

        // 加载关联
        $users->loadMissing($include);

        return $users;
    }

    /**
     * @param $cacheData
     * @param null $limit
     * @return Collection
     */
    public function search($limit, $cacheData = null)
    {
        $query = $this->users->query()->select('users.*')
        ->where('status', 0)
        ->whereBetween('login_at', [Carbon::parse('-30 days'), Carbon::now()])
        ->orderBy('thread_count', 'desc')
        ->orderBy('login_at', 'desc');
        // cache
        if ($cacheData) {
            $query->whereIn('id', $cacheData);
        }

        return $query->take($limit)->get();
    }
}
