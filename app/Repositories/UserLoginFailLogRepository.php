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
     * @return
     */
    public function getDataByIp($ip)
    {
        return $this->query()
            ->where(['ip'=>$ip])
            ->sum('count');
    }

    /**
     * Get user last login time.
     *
     * @param $ip
     * @return string
     */
    public function getLastFailTime($ip)
    {
        return $this->query()
            ->where(['ip'=>$ip])
            ->max('updated_at');
    }
}
