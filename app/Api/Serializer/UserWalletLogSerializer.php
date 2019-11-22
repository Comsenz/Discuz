<?php

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserWalletLogSerializer extends AbstractSerializer
{

    protected $type = 'user_wallet_log';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'change_available_amount' => $model->change_available_amount,
            'change_freeze_amount' => $model->change_freeze_amount,
            'change_type' => $model->change_type,
            'change_desc' => $model->change_desc,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at),
        ];
    }

    /**
     * @param $log
     * @return Relationship
     */
    protected function user($log)
    {
        return $this->hasOne($log, UserSerializer::class);
    }

    /**
     * @param $log
     * @return Relationship
     */
    protected function userWallet($log)
    {
        return $this->hasOne($log, UserWalletSerializer::class);
    }
}