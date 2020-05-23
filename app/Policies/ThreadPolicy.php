<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Category;
use App\Models\Thread;
use App\Models\User;
use Discuz\Api\Events\ScopeModelVisibility;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;

class ThreadPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Thread::class;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * @param User $actor
     * @param string $ability
     * @param Thread $thread
     * @return bool|null
     */
    public function can(User $actor, $ability, Thread $thread)
    {
        if ($actor->hasPermission('thread.' . $ability)) {
            return true;
        }

        // 是否在当前分类下有该权限
        if ($thread->category && $actor->hasPermission('category'.$thread->category->id.'.thread.'.$ability)) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        // 过滤不存在用户的内容
        $query->whereExists(function ($query) {
            $query->selectRaw('1')
                ->from('users')
                ->whereColumn('threads.user_id', 'users.id');
        });

        // 隐藏不允许当前用户查看的分类内容。
        $query->whereNotIn('category_id', Category::getIdsWhereCannot($actor, 'viewThreads'));

        // 回收站
        if (! $actor->hasPermission('viewTrashed')) {
            $query->where(function (Builder $query) use ($actor) {
                $query->whereNull('threads.deleted_at')
                    // ->orWhere('threads.user_id', $actor->id) // 作者是否可见
                    ->orWhere(function ($query) use ($actor) {
                        $this->events->dispatch(
                            new ScopeModelVisibility($query, $actor, 'hide')
                        );
                    });
            });
        }

        // 未通过审核的主题
        if (! $actor->hasPermission('thread.approvePosts')) {
            $query->where(function (Builder $query) use ($actor) {
                $query->where('threads.is_approved', Thread::APPROVED)
                    ->orWhere('threads.user_id', $actor->id);
            });
        }
    }

    /**
     * @param User $actor
     * @param Thread $thread
     * @return bool|null
     */
    public function rename(User $actor, Thread $thread)
    {
        if ($thread->user_id == $actor->id || $actor->isAdmin()) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Thread $thread
     * @return bool|null
     */
    public function hide(User $actor, Thread $thread)
    {
        if ($thread->user_id == $actor->id || $actor->isAdmin()) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Thread $thread
     * @return bool
     */
    public function editPrice(User $actor, Thread $thread)
    {
        if ($thread->user_id == $actor->id || $actor->isAdmin()) {
            return true;
        }
    }
}
