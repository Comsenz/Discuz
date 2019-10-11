<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CirclePolicy.php 28830 2019-10-10 16:09 chenkeke $
 */

namespace App\Policies;

use App\Models\Circle;
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
     * @param Model $actor
     * @param Model $model
     * @param string $ability
     * @return bool
     */
    public function canPermission(Model $actor, Model $model, $ability): bool
    {
        if ($actor->hasPermission('circle.'.$ability)) {
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
        if ($actor->cannot('viewDiscussions')) {
            $query->whereRaw('FALSE');
            return;
        }

        // 默认隐藏私人讨论.
        $query->where(function ($query) use ($actor) {
            $query->where('discussions.is_private', false)
                ->orWhere(function ($query) use ($actor) {
                    $this->events->dispatch(
                        new ScopeModelVisibility($actor, $query, 'findPrivate')
                    );
                });
        });

        // 隐藏隐藏的讨论，除非他们是当前的作者或当前用户具有查看隐藏讨论的权限。
        if (! $actor->hasPermission('discussion.hide')) {
            $query->where(function ($query) use ($actor) {
                $query->whereNull('discussions.hidden_at')
                    ->orWhere('discussions.user_id', $actor->id)
                    ->orWhere(function ($query) use ($actor) {
                        $this->events->dispatch(
                            new ScopeModelVisibility($actor, $query, 'hide')
                        );
                    });
            });
        }

        // 隐藏不带注释的讨论，除非它们是由当前用户，或者允许用户编辑讨论的帖子.
        if (! $actor->hasPermission('discussion.editPosts')) {
            $query->where(function ($query) use ($actor) {
                $query->where('discussions.comment_count', '>', 0)
                    ->orWhere('discussions.user_id', $actor->id)
                    ->orWhere(function ($query) use ($actor) {
                        $this->events->dispatch(
                            new ScopeModelVisibility($actor, $query, 'editPosts')
                        );
                    });
            });
        }
    }

}