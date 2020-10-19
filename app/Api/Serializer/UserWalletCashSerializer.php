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

class UserWalletCashSerializer extends AbstractSerializer
{
    protected $type = 'user_wallet_cash';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'cash_sn' => (string)$model->cash_sn,
            'cash_charge' => $model->cash_charge,
            'cash_actual_amount' => $model->cash_actual_amount,
            'cash_apply_amount' => $model->cash_apply_amount,
            'cash_status' => $model->cash_status,
            'cash_type' => $model->cash_type,
            'cash_mobile' => $model->cash_mobile,
            'remark' => $model->remark,
            'trade_no' => $model->trade_no,
            'error_code' => $model->error_code,
            'error_message' => $model->error_message,
            'refunds_status' => $model->refunds_status,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at),
        ];
    }

    /**
     * @param $cash
     * @return Relationship
     */
    protected function user($cash)
    {
        return $this->hasOne($cash, UserSerializer::class);
    }

    /**
     * @param $cash
     * @return Relationship
     */
    protected function userWallet($cash)
    {
        return $this->hasOne($cash, UserWalletSerializer::class);
    }

    /**
     * @param $cash
     * @return Relationship
     */
    protected function wechat($cash)
    {
        return $this->hasOne($cash->user, UserWechatSerializer::class);
    }
}
