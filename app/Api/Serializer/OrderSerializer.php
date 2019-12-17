<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: PaySerializer.php xxx  2019-10-15 16:56 zhouzhou $
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class OrderSerializer extends AbstractSerializer
{
    protected $type = 'order';

    public function getDefaultAttributes($model)
    {
        return [
            'order_sn'   => (string)$model->order_sn,
            'amount'     => $model->amount,
            'status'     => $model->status,
            'type'       => $model->type,
            'thread_id'    => $model->thread_id,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at),
        ];
    }

    /**
     * @param $orders
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
    protected function thread($order)
    {
        return $this->hasOne($order, ThreadSerializer::class);
    }
    
    /**
     * @param $thread
     * @return Relationship
     */
    public function firstPost($order)
    {
        return $this->hasOne($order, PostSerializer::class);
    }
}
