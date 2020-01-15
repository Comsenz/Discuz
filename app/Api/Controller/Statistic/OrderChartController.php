<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Statistic;

use App\Api\Serializer\FinanceSerializer;
use App\Commands\Statistic\OrderChart;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class OrderChartController extends AbstractResourceController
{

    public $serializer = FinanceSerializer::class;
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
        $filter = $this->extractFilter($request);

        return $this->bus->dispatch(new OrderChart($actor, $filter));
    }
}
