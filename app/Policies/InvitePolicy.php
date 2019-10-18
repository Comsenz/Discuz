<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: InvitePolicy.php 28830 2019-10-12 16:00 chenkeke $
 */

namespace App\Policies;


use App\Models\Invite;
use Discuz\Api\Events\ScopeModelVisibility;
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
     * @param Model $actor
     * @param Model $model
     * @param string $ability
     * @return bool|null
     */
    public function canPermission(Model $actor, Model $model, $ability)
    {
        if ($actor->hasPermission('invite.'.$ability)) {
            return true;
        }
    }

    /**
     * @param Model $actor
     * @param Builder $query
     * @return void
     */
    public function findVisibility(Model $actor, Builder $query)
    {
        // 当前用户是否有权限查看
        if ($actor->cannot('viewDiscussions')) {
            $query->whereRaw('FALSE');
            return;
        }
    }

    /**
     * @param Model $actor
     * @param Builder $query
     * @return void
     */
    public function findEditVisibility(Model $actor, Builder $query)
    {
        if ($actor->cannot('editInvite')) {
            $query->where('invites.user_id', $actor->id);
        }
    }

}