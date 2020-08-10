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

class UserWalletLogSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'user_wallet_log';

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'user_id'                   => $model->user_id,
            'source_user_id'            => $model->source_user_id,
            'change_available_amount'   => $model->change_available_amount,
            'change_freeze_amount'      => $model->change_freeze_amount,
            'change_type'               => $model->change_type,
            'change_desc'               => $model->change_desc,
            'updated_at'                => $this->formatDate($model->updated_at),
            'created_at'                => $this->formatDate($model->created_at),
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
    protected function order($log)
    {
        return $this->hasOne($log, OrderSerializer::class);
    }

    /**
     * @param $log
     * @return Relationship
     */
    protected function sourceUser($log)
    {
        return $this->hasOne($log, UserSerializer::class);
    }
}
