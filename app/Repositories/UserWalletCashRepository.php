<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\UserWalletCash;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UserWalletCashRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the categories table.
     *
     * @return Builder
     */
    public function query()
    {
        return UserWalletCash::query();
    }

    public function findCashOrFail($id, User $actor = null)
    {
        $query = $this->query()->where('id', $id);
        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
