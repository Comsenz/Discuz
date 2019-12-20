<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CirclePolicy.php 28830 2019-10-10 16:09 chenkeke $
 */

namespace App\Policies;

use App\Models\Circle;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Discuz\Api\Events\ScopeModelVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CirclePolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Circle::class;

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
        // 当前用户是否有权限查看站点
        if ($actor->cannot('viewDiscussions')) {
            $query->whereRaw('FALSE');
            return;
        }

        // 隐藏私密站点，除非他们是站点成员或当前用户具有查看私密站点的权限。
        if (!$actor->hasPermission('circles.private')) {
            $query->where(function ($query) use ($actor) {
                $query->whereIn('circles.property', [0, 1])
                    ->orWhereExists(function ($query) use ($actor) {
                        $query->selectRaw('1')
                            ->from('circle_users')
                            ->where('circle_users.user_id', $actor->id)
                            ->whereColumn('circles.id', 'circle_users.circle_id');
                    })
                    ->orWhere(function ($query) use ($actor) {
                        $this->events->dispatch(
                            new ScopeModelVisibility($actor, $query, 'hide')
                        );
                    });
            });
        }
    }

}
