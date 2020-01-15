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
            'id'                  => $model->id,
            'income'              => $model->income,
            'order_count'         => $model->order_count,
            'order_royalty'       => $model->order_royalty,
            'register_profit'     => $model->register_profit,
            'master_portion'      => $model->master_portion,
            'withdrawal_profit'   => $model->withdrawal_profit,
            'created_at'          => $model->created_at
        ];
    }

}
