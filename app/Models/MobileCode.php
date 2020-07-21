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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $mobile
 * @property string $code
 * @property string $type
 * @property string $ip
 * @property int $state
 * @property \Carbon\Carbon $expired_at
 * @property mixed user
 */
class MobileCode extends Model
{
    const USED_STATE = 1;

    protected $fillable = ['mobile', 'code', 'type', 'expired_at'];

    /**
     * @param $mobile
     * @param $exception
     * @param $type
     * @param $ip
     * @return MobileCode
     * @throws \Exception
     */
    public static function make($mobile, $exception, $type, $ip)
    {
        $mobileCode = new static();
        $mobileCode->mobile = $mobile;
        $mobileCode->code = static::genCode();
        $mobileCode->ip = $ip;
        $mobileCode->expired_at = Carbon::now()->addMinutes($exception);
        $mobileCode->type = $type;
        return $mobileCode;
    }

    public function refrecode($exception, $ip)
    {
        $this->code = static::genCode();
        $this->ip = $ip;
        $this->expired_at = Carbon::now()->addMinutes($exception);
        return $this;
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected static function genCode()
    {
        return random_int(100000, 999999);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'mobile', 'mobile');
    }

    public function changeState($status)
    {
        $this->state = $status;
        return $this;
    }
}
