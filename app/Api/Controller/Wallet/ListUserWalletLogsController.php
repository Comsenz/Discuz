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

namespace App\Api\Controller\Wallet;

use App\Api\Serializer\UserWalletLogSerializer;
use App\Models\User;
use App\Models\UserWalletLog;
use App\Repositories\UserWalletLogsRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUserWalletLogsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletLogSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'order',
        'order.user',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'userWallet',
        'sourceUser',
        'userWalletCash',
        'order.thread',
        'order.thread.user',
        'order.thread.firstPost',
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
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var UserWalletLogsRepository
     */
    protected $walletLogs;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $sumChangeAvailableAmount;

    /**
     * @param Dispatcher $bus
     * @param UrlGenerator $url
     * @param UserWalletLogsRepository $walletLogs
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url, UserWalletLogsRepository $walletLogs)
    {
        $this->bus = $bus;
        $this->url = $url;
        $this->walletLogs = $walletLogs;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return Collection|mixed
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
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

        $walletLogs = $this->search($actor, $filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('wallet.log.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->total
        );

        $document->setMeta([
            'total' => $this->total,
            'pageCount' => ceil($this->total / $limit),
            'sumChangeAvailableAmount' => $this->sumChangeAvailableAmount,
        ]);

        // 主题标题
        if (in_array('order.thread.firstPost', $include)) {
            $walletLogs->load('order.thread.firstPost')
                ->map(function (UserWalletLog $log) {
                    if ($log->order && $log->order->thread) {
                        if ($log->order->thread->title) {
                            $title = Str::limit($log->order->thread->title, 40);
                        } else {
                            $title = Str::limit($log->order->thread->firstPost->content, 40);
                            $title = str_replace("\n", '', $title);
                        }

                        $log->order->thread->title = strip_tags($title);
                    }
                });
        }

        $walletLogs = $walletLogs->loadMissing($include);

        return $walletLogs;
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
        $query = $this->walletLogs->query()->whereVisibleTo($actor);

        $this->applyFilters($query, $filter, $actor);

        $this->total = $limit > 0 ? $query->count() : null;

        // 求和变动可用金额
        $this->sumChangeAvailableAmount = number_format($query->sum('change_available_amount'), 2);

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
        $log_user = (int)Arr::get($filter, 'user'); //用户
        $log_change_desc = Arr::get($filter, 'change_desc'); //变动描述
        $log_change_type = Arr::get($filter, 'change_type'); //变动类型
        $log_username = Arr::get($filter, 'username'); //变动钱包所属人
        $log_start_time = Arr::get($filter, 'start_time'); //变动时间范围：开始
        $log_end_time = Arr::get($filter, 'end_time'); //变动时间范围：结束
        $log_source_user_id = Arr::get($filter, 'source_user_id');
        $log_change_type_exclude = Arr::get($filter, 'change_type_exclude');//排除变动类型

        $query->when($log_user, function ($query) use ($log_user) {
            $query->where('user_id', $log_user);
        });
        $query->when($log_change_desc, function ($query) use ($log_change_desc) {
            $query->where('change_desc', 'like', "%$log_change_desc%");
        });
        $query->when(!is_null($log_change_type), function ($query) use ($log_change_type) {
            $log_change_type = explode(',', $log_change_type);
            $query->whereIn('change_type', $log_change_type);
        });
        $query->when(!is_null($log_change_type_exclude), function ($query) use ($log_change_type_exclude) {
            $log_change_type_exclude = explode(',', $log_change_type_exclude);
            $query->whereNotIn('change_type', $log_change_type_exclude);
        });
        $query->when($log_start_time, function ($query) use ($log_start_time) {
            $query->where('created_at', '>=', $log_start_time);
        });
        $query->when($log_end_time, function ($query) use ($log_end_time) {
            $query->where('created_at', '<=', $log_end_time);
        });
        $query->when($log_username, function ($query) use ($log_username) {
            $query->whereIn('user_wallet_logs.user_id', User::where('users.username', $log_username)->select('id', 'username')->get());
        });
        if (Arr::has($filter, 'source_username')) { // 有搜索 "0" 的情况
            $log_source_username = Arr::get($filter, 'source_username');
            $query->whereIn('user_wallet_logs.source_user_id', User::query()->where('users.username', 'like', '%' . $log_source_username . '%')->pluck('id'));
        }
        $query->when($log_source_user_id, function ($query) use ($log_source_user_id) {
            $query->where('source_user_id', '=', $log_source_user_id);
        });
    }
}
