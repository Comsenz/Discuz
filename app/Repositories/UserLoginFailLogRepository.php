<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
