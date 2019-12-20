<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Order;

use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;
use Discuz\Auth\AssertPermissionTrait;
use App\Repositories\OrderRepository;
use App\Api\Serializer\OrderSerializer;

class ResourceOrderController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = OrderSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $order;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'thread',
        'thread.firstPost'
    ];

    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $order_sn = Arr::get($request->getQueryParams(), 'order_sn');
        return $this->order->findOrderOrFail($order_sn, $actor);
    }
}
