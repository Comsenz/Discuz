<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserWalletLogSerializer extends AbstractSerializer
{
    protected $type = 'user_wallet_log';

    public function getDefaultAttributes($model)
    {
        $title = '';
        if (!empty($model->order_id) && !empty($model->order->thread->firstPost)) {
            $title = $model->order->thread->firstPost->formatContent();
        }
        return [
            'id' => $model->id,
            'change_available_amount' => $model->change_available_amount,
            'change_freeze_amount' => $model->change_freeze_amount,
            'change_type' => $model->change_type,
            'change_desc' => $model->change_desc,
            'title' => $title,
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

    /**
     * @param $log
     * @return Relationship
     */
    protected function userWalletCash($log)
    {
        return $this->hasOne($log, UserWalletCashSerializer::class);
    }

    /**
     * @param $log
     * @return Relationship
     */
    protected function Order($log)
    {
        return $this->hasOne($log, OrderSerializer::class);
    }
}
