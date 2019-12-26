<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wallet;

use Illuminate\Contracts\Bus\Dispatcher;
use App\Api\Serializer\UserWalletLogSerializer;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserWalletLog;
use App\Repositories\UserWalletLogsRepository;

class ListUserWalletLogsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletLogSerializer::class;

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
    protected $wallet_log_repository;

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
    ];

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url, UserWalletLogsRepository $wallet_log_repository)
    {
        $this->bus = $bus;
        $this->url = $url;
        $this->wallet_log_repository = $wallet_log_repository;
    }

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        $actor  = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);
        $sort   = $this->extractSort($request);
        $limit  = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $wallet_log = $this->getWalletLogs($actor, $filter, $limit, $offset, $sort);

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
        ]);
        $load         = $this->extractInclude($request);
        $wallet_log = $wallet_log->load($load);

        return $wallet_log;
    }

    /**
     * @param  $actor
     * @param  $filter
     * @param  $limit
     * @param  $offset
     * @param  $sort
     * @return UserWalletLog
     */
    private function getWalletLogs($actor, $filter, $limit = 0, $offset = 0, $sort = [])
    {
        $log_user    = (int) Arr::get($filter, 'user'); //用户
        $log_change_desc     = Arr::get($filter, 'change_desc'); //变动描述
        $log_change_type     = Arr::get($filter, 'change_type'); //变动类型
        $log_username   = Arr::get($filter, 'username'); //变动钱包所属人
        $log_start_time = Arr::get($filter, 'start_time'); //变动时间范围：开始
        $log_end_time   = Arr::get($filter, 'end_time'); //变动时间范围：结束

        $query = $this->wallet_log_repository->query()->whereVisibleTo($actor);
        $query->when($log_user, function ($query) use ($log_user) {
            $query->where('user_id', $log_user);
        });
        $query->when($log_change_desc, function ($query) use ($log_change_desc) {
            $query->where('change_desc', 'like', "%$log_change_desc%");
        });
        $query->when(!is_null($log_change_type), function ($query) use ($log_change_type) {
            $query->where('change_type', $log_change_type);
        });
        $query->when($log_start_time, function ($query) use ($log_start_time) {
            $query->where('created_at', '>=', $log_start_time);
        });
        $query->when($log_end_time, function ($query) use ($log_end_time) {
            $query->where('created_at', '<=', $log_end_time);
        });
        $query->when($log_username, function ($query) use ($log_username) {
            $query->whereIn('user_wallet_log.user_id', User::where('users.username', $log_username)->select('id', 'username')->get());
        });
        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }
        $this->total = $query->count();
        $query->skip($offset)->take($limit);
        return $query->get();
    }
}
