<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\UserWalletFailLogs;
use Carbon\Carbon;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UserWalletFailLogsRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the categories table.
     *
     * @return Builder
     */
    public function query()
    {
        return UserWalletFailLogs::query();
    }

    /**
     * get fail data by user_id
     * @param $user_id
     * @return int
     */
    public function getCountByUserId($user_id)
    {
        return $this->query()
            ->where('user_id', $user_id)
            ->whereBetween('created_at', [Carbon::today(),Carbon::tomorrow()])
            ->count();
    }
}
