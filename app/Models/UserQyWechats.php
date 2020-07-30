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
 * @property string $qy_userid
 * @property string $nickname
 * @property int $sex
 * @property string $email
 * @property string $mobile
 * @property string $address
 * @property string $headimgurl
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @method static create($user)
 * @method static where(...$params)
 */
class UserQyWechats extends Model
{
    protected $table = 'user_qy_wechats';

    protected $fillable = ['user_id', 'nickname','sex', 'email', 'mobile', 'address', 'headimgurl', 'qy_userid'];

    public static function build(array $data)
    {
        $userQyWechat = new static;
        $userQyWechat->attributes = $data;
        return $userQyWechat;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
