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
        ];
    }
}
