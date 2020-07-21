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
            'cash_tax_ratio'   => $model->cash_tax_ratio,
        ];
    }

    public function getId($model)
    {
        return $model->user_id;
    }

    /**
     * @param $user_wallet
     * @return Relationship
     */
    protected function user($user_wallet)
    {
        return $this->hasOne($user_wallet, UserSerializer::class);
    }
}
