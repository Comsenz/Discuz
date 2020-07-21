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
 * @property int $user_id
 * @property string $mp_openid
 * @property string $dev_openid
 * @property string $min_openid
 * @property string $nickname
 * @property int $sex
 * @property string $province
 * @property string $city
 * @property string $country
 * @property string $headimgurl
 * @property string $privilege
 * @property string $unionid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @method static create($user)
 * @method static where(...$params)
 */
class UserWechat extends Model
{
    protected $fillable = ['user_id', 'mp_openid','dev_openid','min_openid','nickname','sex', 'city', 'province', 'headimgurl', 'unionid'];

    public static function build(array $data)
    {
        $userWechat = new static;
        $userWechat->attributes = $data;
        return $userWechat;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
