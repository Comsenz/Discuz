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

namespace App\Api\Controller\Group;

use App\Api\Serializer\PaidUserGroupSerializer;
use App\Repositories\GroupPaidUserRepository;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Discuz\Auth\AssertPermissionTrait;
use App\Models\User;

class ListPaidUserGroupsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = PaidUserGroupSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'user',
        'group',
        'operator',
        'order'
    ];


    /**
     * {@inheritdoc}
     */
    public $sortFields = [
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    public $sort = [
        'created_at' => 'desc',
    ];
    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int
     */
    protected $total;


    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $filter = $this->extractFilter($request);
        $sort = $this->extractSort($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $orders = $this->search($actor, $filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('groups.paid'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->total
        );

        $document->setMeta([
            'total' => $this->total,
            'pageCount' => ceil($this->total / $limit),
        ]);

        return $orders->loadMissing($include);
    }

    /**
     * @param $actor
     * @param $filter
     * @param $sort
     * @param null $limit
     * @param int $offset
     * @return Collection
     */
    public function search($actor, $filter, $sort, $limit = null, $offset = 0)
    {
        $query = GroupPaidUserRepository::query()->whereVisibleTo($actor);

        $query->withTrashed();

        $this->applyFilters($query, $filter, $actor);

        $this->total = $limit > 0 ? $query->count() : null;

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
        $user = (int)Arr::get($filter, 'user', ''); //所属用户
        $operator = (int)Arr::get($filter, 'operator', '');
        $group = (int)Arr::get($filter, 'group', '');
        $order = (int)Arr::get($filter, 'order', '');
        $delete_type = Arr::get($filter, 'delete_type', '');

        $query->when($user, function ($query) use ($user) {
            $query->where('user_id', $user);
        });
        $query->when($operator, function ($query) use ($operator) {
            $query->where('operator_id', $operator);
        });
        $query->when($group, function ($query) use ($group) {
            $query->where('group_id', $group);
        });

        $query->when($order, function ($query) use ($order) {
            $query->where('order_id', $order);
        });

        $query->when($delete_type !== '', function ($query) use ($delete_type) {
            $query->where('delete_type', (int) $delete_type);
        });
    }
}
