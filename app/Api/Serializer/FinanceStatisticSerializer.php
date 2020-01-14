<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Support\Arr;

class FinanceStatisticSerializer extends AbstractSerializer
{
    protected $type = 'finance_statistic';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'totalIncome'            => number_format(Arr::get($model, 'totalIncome', 0.00), 2),
            'totalExpenditures'      => number_format(Arr::get($model, 'totalExpenditures', 0.00), 2),
            'totalWithdrawal'        => number_format(Arr::get($model, 'totalWithdrawal', 0.00), 2),
            'totalWallet'            => number_format(Arr::get($model, 'totalWallet', 0.00), 2),
            'totalProfit'            => number_format(Arr::get($model, 'totalProfit', 0.00), 2),
            'withdrawalProfit'       => number_format(Arr::get($model, 'withdrawalProfit', 0.00), 2),
            'orderRoyalty'           => number_format(Arr::get($model, 'orderRoyalty', 0.00), 2),
            'orderCount'             => Arr::get($model, 'orderCount', 0),
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
