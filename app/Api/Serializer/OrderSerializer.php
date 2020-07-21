<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
