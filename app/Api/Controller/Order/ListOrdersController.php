<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateOrderController.php xxx 2019-10-24 11:20:00 zhouzhou $
 */

namespace App\Api\Controller\Order;

use App\Api\Serializer\OrderSerializer;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Routing\UrlGenerator;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Repositories\OrderRepository;
use App\Models\Thread;
use App\Models\Post;
use App\Models\User;

class ListOrdersController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = OrderSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * {@inheritdoc}
     */
    public $order;

    /**
     * @var int
     */
    protected $total;

    /**
     * {@inheritdoc}
     */
    public $sort = [
        'orders.created_at' => 'desc',
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
        'thread',
        'user',
        'thread.firstPost',
    ];

    /* The relationships that are included by default.
     *
     * @var array
     */
    public $include = [];

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url, OrderRepository $order)
    {
        $this->bus = $bus;
        $this->url = $url;
        $this->order = $order;
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

        $orders = $this->getOrders($actor, $filter, $limit, $offset, $sort);

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
        $load = $this->extractInclude($request);

        $orders = $orders->load($load);
        return $orders;
    }

    /**
     * @param  $actor
     * @param  $filter
     * @param  $limit
     * @param  $offset
     * @param  $sort
     * @return Order
     */
    private function getOrders($actor, $filter, $limit = 0, $offset = 0, $sort = [])
    {
        $order_user           = (int) Arr::get($filter, 'user'); //订单所属用户
        $status           = Arr::get($filter, 'status'); //订单状态
        $order_sn         = Arr::get($filter, 'order_sn'); //订单编号
        $order_start_time = Arr::get($filter, 'start_time'); //订单创建开始时间
        $order_end_time   = Arr::get($filter, 'end_time'); //订单创建结束时间
        $order_username   = Arr::get($filter, 'username'); //订单创建人
        $order_product    = Arr::get($filter, 'product'); //商品

        $query = $this->order->query()->whereVisibleTo($actor);
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
        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }
        $this->total = $query->count();

        $query->skip($offset)->take($limit);
 
        return $query->get();
    }

}
