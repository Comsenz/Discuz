<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ResourceOrderController.php xxx 2019-10-19 15:40 zhouzhou $
 */

namespace App\Api\Controller\Order;


use App\Api\Serializer\OrderSerializer;
use App\Models\Order;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceOrderController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = OrderSerializer::class;

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {   
        $order_sn = Arr::get($request->getQueryParams(), 'order_sn');
        return Order::where('order_sn', $order_sn)->first();
    }
}