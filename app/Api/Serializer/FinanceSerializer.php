<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class FinanceSerializer extends AbstractSerializer
{
    protected $type = 'finance';

    public function getDefaultAttributes($model)
    {
        return [
            'date'                => $model->date,
            'order_count'         => $model->order_count,
            'order_amount'        => $model->order_amount,
            'total_profit'        => $model->total_profit,
            'register_profit'     => $model->register_profit,
            'master_portion'      => $model->master_portion,
            'withdrawal_profit'   => $model->withdrawal_profit,
        ];
    }
}
