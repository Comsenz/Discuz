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
use Tobscure\JsonApi\Document;
use App\Api\Serializer\OrderSerializer;
use App\Commands\Order\ListOrder;

class ListOrderController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = OrderSerializer::class;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        // 获取请求的参数
        $query_inputs = $request->getQueryParams();

        return $this->bus->dispatch(
            new ListOrder($actor, $query_inputs)
        );
    }
}
