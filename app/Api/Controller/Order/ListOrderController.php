<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateOrderController.php xxx 2019-10-24 11:20:00 zhouzhou $
 */

namespace App\Api\Controller\Order;

use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use Tobscure\JsonApi\Document;
use App\Api\Serializer\OrderSerializer;
use App\Commands\Order\ListOrder;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ListOrderController extends AbstractListController
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
        'updated_at'
    ];


    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url)
    {
        $this->bus = $bus;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'user',
    ];

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);
        $sort = $this->extractSort($request);
        $limit = $this->extractLimit($request);;
        $offset = $this->extractOffset($request);

        $status = Arr::get($filter, 'status');
        $orders = $this->getOrders($actor, $status, $limit, $offset, $sort);

        $document->addPaginationLinks(
            $this->url->route('order.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->total
        );

        $load = $this->extractInclude($request);
        $orders = $orders->load($load);

        return $orders;
    }

    /**
     * @param  $actor
     * @param  $status
     * @param  $limit
     * @param  $offset
     * @param  $sort
     * @return Order
     */
    private function getOrders($actor, $status, $limit = 0, $offset = 0, $sort = [])
    {
        $query = $actor->orders();
        $query->when($status, function($query) use ($status){
                $query->where('status', $status);
            });
        $query->skip($offset)->take($limit);
        if (empty($sort)) {
            $query->orderBy('created_at', 'desc');
        } else {
            foreach ((array) $sort as $field => $order) {
                $query->orderBy(Str::snake($field), $order);
            }
        }
        $this->total = $query->count();
        return $query->get();
    }

}
