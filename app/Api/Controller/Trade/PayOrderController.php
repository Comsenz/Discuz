<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: PayOrderController.php xxx 2019-10-16 00:00:00 zhouzhou $
 */

namespace App\Api\Controller\Trade;

use App\Api\Serializer\PayOrderSerializer;
use App\Commands\Trade\PayOrder;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class PayOrderController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = PayOrderSerializer::class;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: User $actor 用户模型
        $actor = $request->getAttribute('actor');

        //订单编号
        $order_sn = Arr::get($request->getQueryParams(), 'order_sn');

        return $this->bus->dispatch(
            new PayOrder($order_sn, $actor, $request->getParsedBody())
        );
    }
}
