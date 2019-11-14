<?php

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserWalletCashSerializer extends AbstractSerializer
{

    protected $type = 'user_wallet_cash';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'cash_sn' => $model->cash_sn,
            'cash_charge' => $model->cash_charge,
            'cash_actual_amount' => $model->cash_actual_amount,
            'cash_apply_amount' => $model->cash_apply_amount,
            'cash_status' => $model->cash_status,
            'remark' => $model->remark,
            'updated_at' => $model->updated_at,
            'created_at' => $model->created_at,
        ];
    }
}
