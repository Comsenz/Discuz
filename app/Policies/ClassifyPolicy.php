<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ClassifyPolicy.php 28830 2019-10-14 11:52 chenkeke $
 */

namespace App\Policies;


use App\Models\Classify;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ClassifyPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Classify::class;

    /**
     * @param Model $actor
     * @param Model $model
     * @param string $ability
     * @return bool
     */
    public function canPermission(Model $actor, Model $model, $ability): bool
    {
        if ($actor->hasPermission('invite.'.$ability)) {
            return true;
        }
        return true;
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