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

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $payment_sn
 * @property int $user_id
 * @property string $trade_no
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @package App\Models
 */
class PayNotify extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 通知状态
     */
    const NOTIFY_STATUS_PENDING  = 0; //未收到通知

    /**
     * 通知状态
     */
    const NOTIFY_STATUS_RECEIVED = 1; //已收到通知

    /**
     * {@inheritdoc}
     */
    protected $table = 'pay_notify';

    /**
     * Define the relationship with the pay_notify's order.
     *
     * @return belongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'payment_sn', 'payment_sn');
    }
}
