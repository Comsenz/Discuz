<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\UserWalletLog;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UserWalletLogsRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the categories table.
     *
     * @return Builder
     */
    public function query()
    {
        return UserWalletLog::query();
    }
}
