<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\UserWallet;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UserWalletRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the categories table.
     *
     * @return Builder
     */
    public function query()
    {
        return UserWallet::query();
    }

    public function findOrFail($id, User $actor = null)
    {
        $query = UserWallet::where('user_id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
