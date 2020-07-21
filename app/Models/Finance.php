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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property float $income
 * @property float $withdrawal
 * @property int $order_count
 * @property float $order_amount
 * @property float $total_profit
 * @property float $register_profit
 * @property float $master_portion
 * @property float $withdrawal_profit
 * @property Carbon $created_at
 * @package App\Models
 */
class Finance extends Model
{
    const UPDATED_AT = null;

    const TYPE_DAYS = 1;   //统计方式（日）

    const TYPE_WEEKS = 2;  //统计方式（周）

    const TYPE_MONTH = 3;  //统计方式（月）

    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $table = 'finance';

    protected $fillable = [
        'income',
        'withdrawal',
        'order_count',
        'order_amount',
        'total_profit',
        'register_profit',
        'master_portion',
        'withdrawal_profit',
        'created_at',
        ];
}
