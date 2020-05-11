<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Trade;

use App\Api\Serializer\PayOrderSerializer;
use App\Commands\Trade\PayOrder;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
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
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $order_sn = Arr::get($request->getQueryParams(), 'order_sn');

        return $this->bus->dispatch(
            new PayOrder($order_sn, $actor, $request->getParsedBody())
        );
    }
}
