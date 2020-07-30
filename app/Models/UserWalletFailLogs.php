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
 * @property string $ip
 * @property int $user_id
 * @property Carbon $created_at
 * @package App\Models
 */
class UserWalletFailLogs extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'user_wallet_fail_logs';

    public $timestamps = false;

    const TOPLIMIT = 2;

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * @param $ip
     * @param $user_id
     * @return bool
     */
    public static function build($ip, $user_id)
    {
        $log = new static();
        $log->ip = $ip;
        $log->user_id = $user_id;
        $log->created_at = Carbon::now();
        return $log->save();
    }

    public static function deleteAll($user_id)
    {
        self::query()->where('user_id', $user_id)->delete();
    }
}
