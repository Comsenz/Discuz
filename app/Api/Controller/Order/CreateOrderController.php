<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Order;

use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Contracts\Bus\Dispatcher;
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
    public $include = [
        'user',
        'thread',
        'thread.firstPost'
    ];

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
        $body = $request->getParsedBody();

        return $this->bus->dispatch(
            new CreateOrder($actor, $body)
        );
    }
}
