<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Support\Arr;

class MoneyStatisticSerializer extends AbstractSerializer
{
    protected $type = 'money_statistic';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'totalIncome'            => Arr::get($model, 'totalIncome', 0),
            'freezingAmount'         => Arr::get($model, 'freezingAmount', 0),
            'totalExpenditures'      => Arr::get($model, 'totalExpenditures', 0),
        ];
    }

    public function getId($model)
    {
        return 1;
    }
}
