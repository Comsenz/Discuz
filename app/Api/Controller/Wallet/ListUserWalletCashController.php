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

use App\Api\Serializer\UserWalletCashSerializer;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\User;
use App\Repositories\UserWalletCashRepository;
use Discuz\Auth\AssertPermissionTrait;

class ListUserWalletCashController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletCashSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var UserWalletCashRepository
     */
    protected $cash;

    /**
     * @var int
     */
    protected $total;

    /**
     * {@inheritdoc}
     */
    public $sort = [
        'created_at' => 'desc',
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
    public $optionalInclude = [
        'user',
        'userWallet',
        'wechat'
    ];

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url, UserWalletCashRepository $cash)
    {
        $this->bus = $bus;
        $this->url = $url;
        $this->cash = $cash;
    }

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        $actor  = $request->getAttribute('actor');
        $this->assertRegistered($actor);
        $filter = $this->extractFilter($request);
        $sort   = $this->extractSort($request);
        $limit  = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $cash_records = $this->getCashRecords($actor, $filter, $limit, $offset, $sort);

        $document->addPaginationLinks(
            $this->url->route('wallet.cash.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->total
        );
        $document->setMeta([
            'total' => $this->total,
            'pageCount' => ceil($this->total / $limit),
        ]);

        $include = $this->extractInclude($request);

        return $cash_records->load($include);
    }

    /**
     * @param  $actor
     * @param  $filter
     * @param  $limit
     * @param  $offset
     * @param  $sort
     * @return Order
     */
    private function getCashRecords($actor, $filter, $limit = 0, $offset = 0, $sort = [])
    {
        $cash_user         = (int) Arr::get($filter, 'user'); //提现用户
        $cash_sn         = Arr::get($filter, 'cash_sn'); //提现流水号
        $cash_status     = Arr::get($filter, 'cash_status'); //提现状态
        $cash_username   = Arr::get($filter, 'username'); //提现人
        $cash_type       = Arr::get($filter, 'cash_type'); //提现方式
        $cash_mobile     = Arr::get($filter, 'cash_mobile'); //提现到的手机号码
        $cash_start_time = Arr::get($filter, 'start_time'); //申请时间范围：开始
        $cash_end_time   = Arr::get($filter, 'end_time'); //申请时间范围：结束

        $query = $this->cash->query()->whereVisibleTo($actor);
        $query->when($cash_user, function ($query) use ($cash_user) {
            $query->where('user_id', $cash_user);
        });
        $query->when($cash_sn, function ($query) use ($cash_sn) {
            $query->where('cash_sn', $cash_sn);
        });

        $query->when(!is_null($cash_status), function ($query) use ($cash_status) {
            $query->where('cash_status', $cash_status);
        });

        $query->when(!is_null($cash_type), function ($query) use ($cash_type) {
            $query->where('cash_type', $cash_type);
        });
        $query->when($cash_mobile, function ($query) use ($cash_mobile) {
            $query->where('cash_mobile', $cash_mobile);
        });

        $query->when($cash_start_time, function ($query) use ($cash_start_time) {
            $query->where('created_at', '>=', $cash_start_time);
        });
        $query->when($cash_end_time, function ($query) use ($cash_end_time) {
            $query->where('created_at', '<=', $cash_end_time);
        });
        $query->when($cash_username, function ($query) use ($cash_username) {
            $query->whereIn('user_wallet_cash.user_id', User::where('users.username', $cash_username)->select('id', 'username')->get());
        });
        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }
        $this->total = $query->count();
        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
