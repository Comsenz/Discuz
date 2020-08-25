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

namespace App\Api\Controller\Report;

use App\Api\Serializer\ReportsSerializer;
use App\Models\Report;
use App\Models\User;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListReportsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = ReportsSerializer::class;

    /**
     * 传输关系
     *
     * {@inheritdoc}
     */
    public $optionalInclude = [];

    /**
     * 默认关系
     *
     * {@inheritdoc}
     */
    public $include = [
        'user',
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
     * @var int|null
     */
    protected $reportCount;

    /**
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertPermission($actor->isAdmin());

        $filter = $this->extractFilter($request);
        $sort = $this->extractSort($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $reports = $this->search($actor, $filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('reports.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->reportCount
        );

        $document->setMeta([
            'total' => $this->reportCount,
            'pageCount' => ceil($this->reportCount / $limit),
        ]);

        $reports->loadMissing($include);

        return $reports;
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
        $query = Report::query();

        $this->applyFilters($query, $filter, $actor);

        $this->reportCount = $limit > 0 ? $query->count() : null;

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
        $startTime = Arr::get($filter, 'start_time', '');
        $endTime = Arr::get($filter, 'end_time', '');
        $username = Arr::get($filter, 'username', '');

        if (Arr::has($filter, 'status')) {
            $query->where('status', Arr::get($filter, 'status'));
        }

        if (Arr::has($filter, 'type')) {
            $query->where('type', Arr::get($filter, 'type'));
        }

        $query->when($startTime, function ($query, $startTime) {
            $query->where('created_at', '>', $startTime);
        });

        $query->when($endTime, function ($query, $endTime) {
            $query->where('created_at', '<', $endTime);
        });

        $query->when($username, function ($query, $username) {
            $query->whereIn('user_id', User::query()->where('username', 'like', "%{$username}%")->pluck('id'));
        });
    }
}
