<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserWalletCashSerializer extends AbstractSerializer
{
    protected $type = 'user_wallet_cash';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'cash_sn' => (string)$model->cash_sn,
            'cash_charge' => $model->cash_charge,
            'cash_actual_amount' => $model->cash_actual_amount,
            'cash_apply_amount' => $model->cash_apply_amount,
            'cash_status' => $model->cash_status,
            'remark' => $model->remark,
            'trade_no' => $model->trade_no,
            'error_code' => $model->error_code,
            'error_message' => $model->error_message,
            'refunds_status' => $model->refunds_status,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at),
        ];
    }

    /**
     * @param $cash
     * @return Relationship
     */
    protected function user($cash)
    {
        return $this->hasOne($cash, UserSerializer::class);
    }

    /**
     * @param $cash
     * @return Relationship
     */
    protected function userWallet($cash)
    {
        return $this->hasOne($cash, UserWalletSerializer::class);
    }
}
