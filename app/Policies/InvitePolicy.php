<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Invite;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InvitePolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Invite::class;

    /**
     * @param User $actor
     * @param Model $model
     * @param string $ability
     * @return bool|null
     */
    public function canPermission(User $actor, Model $model, $ability)
    {
        if ($actor->hasPermission($this->getAbility($ability))) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     * @return void
     */
    public function findVisibility(User $actor, Builder $query)
    {
        // 当前用户是否有权限查看
        if ($actor->cannot('viewDiscussions')) {
            $query->whereRaw('FALSE');
            return;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     * @return void
     */
    public function findEditVisibility(User $actor, Builder $query)
    {
        if ($actor->cannot('editInvite')) {
            $query->where('invites.user_id', $actor->id);
        }
    }
}
