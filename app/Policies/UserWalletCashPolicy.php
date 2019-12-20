<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\UserWalletCash;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class UserWalletCashPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = UserWalletCash::class;

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        if ($actor->cannot('cash.viewList')) {
            $query->where('user_id', $actor->id);
            return;
        }
    }
}
