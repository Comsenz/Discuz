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

namespace App\Repositories;

use App\Models\UserLoginFailLog;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class UserLoginFailLogRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the user login log table.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return UserLoginFailLog::query();
    }

    /**
     * Get user fail login log num limit 5 by ip.
     * @param $ip
     * @param $username
     * @return mixed
     */
    public function getCount($ip, $username)
    {
        return $this->query()
            ->where(['ip'=>$ip])
            ->where(['username'=>$username])
            ->sum('count');
    }

    /**
     * Get user last login time.
     *
     * @param $ip
     * @param $username
     * @return string
     */
    public function getLastFailTime($ip, $username)
    {
        return $this->query()
            ->where(['ip'=>$ip])
            ->where(['username'=>$username])
            ->max('updated_at');
    }
}
