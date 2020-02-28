<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Order;

use App\Api\Serializer\OrderSerializer;
use App\Models\Order;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\OrderRepository;
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

class ListOrdersController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = OrderSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'thread',
        'thread.user',
        'thread.firstPost',
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
     * @var OrderRepository
     */
    protected $orders;

    /**
     * @var int
     */
    protected $total;

    /**
     * @param Dispatcher $bus
     * @param UrlGenerator $url
     * @param OrderRepository $orders
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url, OrderRepository $orders)
    {
        $this->bus = $bus;
        $this->url = $url;
        $this->orders = $orders;
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
        $load = $this->extractInclude($request);

        $orders = $this->search($actor, $filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('order.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->total
        );

        $document->setMeta([
            'total' => $this->total,
            'pageCount' => ceil($this->total / $limit),
        ]);

        // 主题标题
        if (in_array('thread.firstPost', $load)) {
            $orders->load('thread.firstPost')
                ->map(function (Order $order) {
                    if ($order->thread->is_long_article) {
                        $title = Str::limit($order->thread->title, 40);
                    } else {
                        $title = Str::limit($order->thread->firstPost->content, 40);
                        $title = str_replace("\n", '', $title);
                    }

                    $order->thread->title = $title;
                });
        }

        $orders = $orders->loadMissing($load);

        return $orders;
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
        $query = $this->orders->query()->whereVisibleTo($actor);

        $this->applyFilters($query, $filter, $actor);

        $this->total = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
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
        $order_user = (int) Arr::get($filter, 'user'); //订单所属用户
        $status = Arr::get($filter, 'status'); //订单状态
        $order_sn = Arr::get($filter, 'order_sn'); //订单编号
        $order_start_time = Arr::get($filter, 'start_time'); //订单创建开始时间
        $order_end_time = Arr::get($filter, 'end_time'); //订单创建结束时间
        $order_username = Arr::get($filter, 'username'); //订单创建人
        $order_product = Arr::get($filter, 'product'); //商品

        $query->when(!is_null($status), function ($query) use ($status) {
            $query->where('status', $status);
        });
        $query->when($order_user, function ($query) use ($order_user) {
            $query->where('user_id', $order_user);
        });
        $query->when($order_sn, function ($query) use ($order_sn) {
            $query->where('order_sn', $order_sn);
        });
        $query->when($order_start_time, function ($query) use ($order_start_time) {
            $query->where('created_at', '>=', $order_start_time);
        });
        $query->when($order_end_time, function ($query) use ($order_end_time) {
            $query->where('created_at', '<=', $order_end_time);
        });
        $query->when($order_username, function ($query) use ($order_username) {
            $query->whereIn('orders.user_id', User::where('users.username', $order_username)->select('id', 'username')->get());
        });
        $query->when($order_product, function ($query) use ($order_product) {
            $query->whereIn('orders.thread_id', Thread::whereIn('threads.id', Post::where('content', 'like', "%$order_product%")->select('posts.thread_id')->groupBy('posts.thread_id')->get())->select('threads.id')->get());
        });
    }
}
