<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadPolicy.php xxx 2019-10-30 19:55:00 LiuDongdong $
 */

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ThreadPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Thread::class;

    /**
     * @param User $actor
     * @param Model $model
     * @param string $ability
     * @return bool|null
     */
    public function canPermission(User $actor, Model $model, $ability)
    {
        dd(__FUNCTION__);
        if ($actor->hasPermission($this->getAbility($ability))) {
            return true;
        }
    }

    /**
     * 当前用户是否有权限查看主题
     *
     * {@inheritdoc}
     */
    public function findVisibility(User $actor, Builder $query)
    {
        // 暂时添加是否可以查看已删除主题
        if ($actor->id == 1) {
            $query->withTrashed();
            return;
        }
    }

    public function readThreadVisibility(User $actor, Builder $query)
    {
        dd($actor, $query);
    }
}
