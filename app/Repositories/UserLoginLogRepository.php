<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UserLoginLogRepository.php  2019-12-18 11:49 Xinghailong $
 */

namespace App\Repositories;


use App\Models\UserLoginLog;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class UserLoginLogRepository extends AbstractRepository
{

    /**
     * Get a new query builder for the user login log table.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return UserLoginLog::query();
    }

    /**
     * Get user fail login log num limit 5.
     * @param $user_id
     * @return int
     */
    public function getUserLoginLogFailuresCount($user_id){
        return $this->query()
            ->where(['user_id'=>$user_id])
            ->take(5)
            ->orderBy('created_at','desc')->pluck('type')->sum();
    }

    /**
     * Get user last login time.
     *
     * @param $user_id
     * @return datetime
     */
    public function getLastLoginTime($user_id){
        return $this->query()
            ->where(['user_id'=>$user_id])
            ->max('created_at');
    }
}
