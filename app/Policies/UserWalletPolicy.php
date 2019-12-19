<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\UserWallet;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class UserWalletPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = UserWallet::class;

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        if ($actor->cannot('wallet.viewList')) {
            $query->where('user_id', $actor->id);
            return;
        }
    }
}
