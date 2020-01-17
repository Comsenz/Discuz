<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Statistic;

use App\Api\Serializer\FinanceSerializer;
use App\Commands\Statistic\FinanceChart;
use App\Models\Finance;
use Carbon\Carbon;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class FinanceChartController extends AbstractListController
{
    const CREATE_AT_BEGIN = '-60 days'; //默认统计周期

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

        $type = Arr::get($filter, 'type', Finance::TYPE_DAYS);
        $createdAtBegin = Arr::get($filter, 'createdAtBegin', Carbon::parse(self::CREATE_AT_BEGIN)->toDateString());
        $createdAtEnd = Arr::get($filter, 'createdAtEnd', Carbon::now()->toDateString());

        return $this->bus->dispatch(new FinanceChart($actor, $type, $createdAtBegin, $createdAtEnd));
    }
}
