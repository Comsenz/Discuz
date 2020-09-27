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

class PaidUserGroupSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'group_paid_user';

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        return [
            'user_id'               => $model->user_id,
            'group_id'              => $model->group_id,
            'order_id'              => $model->order_id,
            'operator_id'           => $model->operator_id,
            'delete_type'           => $model->delete_type,
            'expiration_time'       => $this->formatDate(new \DateTime($model->expiration_time)),
            'updated_at'            => $this->formatDate($model->updated_at),
            'created_at'            => $this->formatDate($model->created_at),
            'deleted_at'            => $this->formatDate($model->deleted_at),
        ];
    }

    /**
     * @param $model
     * @return Relationship
     */
    protected function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    /**
     * @param $model
     * @return Relationship
     */
    protected function group($model)
    {
        return $this->hasOne($model, GroupSerializer::class);
    }

    /**
     * @param $model
     * @return Relationship
     */
    protected function operator($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    /**
     * @param $model
     * @return Relationship
     */
    protected function order($model)
    {
        return $this->hasOne($model, OrderSerializer::class);
    }
}
