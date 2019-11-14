<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateOrderController.php xxx 2019-10-16 00:00:00 zhouzhou $
 */

namespace App\Api\Controller\Order;

use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Api\Serializer\OrderSerializer;
use App\Commands\Order\CreateOrder;


class CreateOrderController extends AbstractCreateController
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
        // TODO: User $actor 用户模型
        $actor = $request->getAttribute('actor');
        $inputs    = $request->getParsedBody();

        return $this->bus->dispatch(
            new CreateOrder($actor, $inputs)
        );
    }
}
