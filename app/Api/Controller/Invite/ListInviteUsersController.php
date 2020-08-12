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

namespace App\Api\Controller\Invite;

use App\Api\Serializer\InviteUserSerializer;
use App\Models\Invite;
use App\Models\User;
use App\Models\UserDistribution;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListInviteUsersController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = InviteUserSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['user'];

    /**
     * {@inheritdoc}
     */
    public $sortFields = ['created_at'];

    /**
     * @var Invite
     */
    protected $invite;

    /**
     * @var int|null
     */
    protected $inviteCount;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var UserDistribution
     */
    protected $distribution;

    /**
     * @param Invite $invite
     * @param UserDistribution $distribution
     * @param UrlGenerator $url
     */
    public function __construct(Invite $invite, UserDistribution $distribution, UrlGenerator $url)
    {
        $this->invite = $invite;
        $this->distribution = $distribution;
        $this->url = $url;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return Collection|mixed
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertRegistered($actor);

        $filter = $this->extractFilter($request);
        $sort = $this->extractSort($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $distribution = $this->search($actor, $filter, $sort, $limit, $offset);

        $distribution->load($include);

        $document->addPaginationLinks(
            $this->url->route('invite.user.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->inviteCount
        );

        $document->setMeta([
            'total' => $this->inviteCount,
            'pageCount' => ceil($this->inviteCount / $limit),
        ]);

        return $distribution;
    }

    /**
     * @param $actor
     * @param $filter
     * @param $sort
     * @param int|null $limit
     * @param int $offset
     *
     * @return Collection
     */
    public function search($actor, $filter, $sort, $limit = null, $offset = 0)
    {
        $query = $this->distribution->query()->whereVisibleTo($actor);

        $query->where('pid', $actor->id)->with('user');

        $this->applyFilters($query, $filter, $actor);

        $this->inviteCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array)$sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        return $query->get();
    }

    /**
     * @param Builder $query
     * @param array $filter
     * @param User $actor
     */
    private function applyFilters(Builder $query, array $filter, User $actor)
    {
        $username = Arr::get($filter, 'username');

        if (Arr::has($filter, 'scale')) {
            $scale = (int)Arr::get($filter, 'scale', 0);
            if (empty($scale)) {
                $query->where('be_scale', '=', 0);
            } else {
                $query->where('be_scale', '>', 0);
            }
        }

        $query->when($username, function ($query, $username) {
            // 用户名前后存在星号（*）则使用模糊查询
            if (Str::startsWith($username, '*') || Str::endsWith($username, '*')) {
                $username = Str::replaceLast('*', '%', Str::replaceFirst('*', '%', $username));
                $userIds = User::query()->where('username', 'like', $username)->pluck('id');
            } else {
                $userIds = User::query()->where('username', $username)->pluck('id');
            }

            // user_distributions.user_id
            $query->whereIn('user_id', $userIds);
        });
    }
}
