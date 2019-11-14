<?php

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserWalletSerializer extends AbstractSerializer
{

    protected $type = 'user_wallet';

    public function getDefaultAttributes($model)
    {
        return [
            'user_id'          => $model->user_id,
            'available_amount' => $model->available_amount,
            'freeze_amount'    => $model->freeze_amount,
            'wallet_status'    => $model->wallet_status,
        ];
    }
}
