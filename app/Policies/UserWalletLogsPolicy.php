<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\UserWalletLog;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class UserWalletLogsPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = UserWalletLog::class;

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        if ($actor->cannot('wallet.logs.viewList')) {
            $query->where('user_id', $actor->id);
            return;
        }
    }
}
