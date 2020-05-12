<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class OrderSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'orders';

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        return [
            'order_sn'              => (string)$model->order_sn,
            'amount'                => $model->amount,
            'status'                => $model->status,
            'type'                  => $model->type,
            'thread_id'             => $model->thread_id,
            'group_id'              => $model->group_id,
            'updated_at'            => $this->formatDate($model->updated_at),
            'created_at'            => $this->formatDate($model->created_at),
        ];
    }

    /**
     * @param $order
     * @return Relationship
     */
    protected function user($order)
    {
        return $this->hasOne($order, UserSerializer::class);
    }

    /**
     * @param $order
     * @return Relationship
     */
    protected function payee($order)
    {
        return $this->hasOne($order, UserSerializer::class);
    }

    /**
     * @param $order
     * @return Relationship
     */
    protected function thread($order)
    {
        return $this->hasOne($order, ThreadSerializer::class);
    }

    /**
     * @param $order
     * @return Relationship
     */
    protected function group($order)
    {
        return $this->hasOne($order, GroupSerializer::class);
    }
}
