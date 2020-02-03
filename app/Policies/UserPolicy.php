<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class UserPolicy extends AbstractPolicy
{
    protected $model = User::class;

    /**
     * @param User $actor
     * @param string $ability
     * @return bool|null
     */
    public function can(User $actor, $ability)
    {
        if ($actor->hasPermission('user.' . $ability)) {
            return true;
        }
    }

    public function find(User $actor, Builder $query)
    {
        return true;
    }
}
