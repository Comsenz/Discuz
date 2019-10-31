<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: PostPolicy.php xxx 2019-10-31 19:41:00 LiuDongdong $
 */

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Post::class;

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
     * 当前用户是否有权限查看帖子
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
}
