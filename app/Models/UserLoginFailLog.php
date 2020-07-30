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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $ip
 * @property int $user_id
 * @property string $username
 * @property int $count
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
 */
class UserLoginFailLog extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'user_login_fail_log';

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    /**
     * write user login fail log.
     *
     * @param $ip
     * @param $user_id
     * @param $username
     * @return mixed
     */
    public static function writeLog($ip, $user_id, $username)
    {
        if (self::getDataByIp($ip, $username)) {
            return self::setFailCountByIp($ip, $user_id, $username);
        }

        $log = new static;
        $log->ip = $ip;
        $log->user_id = $user_id;
        $log->username = $username;
        $log->count = 1;
        return $log->save();
    }

    /**
     * add fail count
     *
     * @param $ip
     * @param $user_id
     * @param $username
     * @return mixed
     */
    public static function setFailCountByIp($ip, $user_id, $username)
    {
        if (!self::getDataByIp($ip, $username)) {
            return self::writeLog($ip, $user_id, $username);
        }

        return self::query()
            ->where(['ip'=>$ip,'username'=>$username])
            ->increment('count');
    }

    /**
     * refresh fail count
     * @param $ip
     * @return int
     */
    public static function reSetFailCountByIp($ip)
    {
        return self::query()
            ->where(['ip'=>$ip])
            ->update(['count'=>0]);
    }

    /**
     * get fail data by ip
     * @param $ip
     * @param $username
     * @return Builder|Model|object|null
     */
    public static function getDataByIp($ip, $username)
    {
        return self::query()
            ->where(['ip'=>$ip,'username'=>$username])
            ->first();
    }
}
