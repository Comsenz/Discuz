<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Discuz\Api\Events\ScopeModelVisibility;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;

class PostPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Post::class;

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
     * @param Post $post
     * @return bool|null
     */
    public function can(User $actor, $ability, Post $post)
    {
        if ($actor->can($ability . 'Posts', $post->thread)) {
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
                ->whereColumn('posts.user_id', 'users.id');
        });

        // 确保帖子所在主题可见。
        $query->whereExists(function ($query) use ($actor) {
            $query->selectRaw('1')
                ->from('threads')
                ->whereColumn('threads.id', 'posts.thread_id');

            $this->events->dispatch(
                new ScopeModelVisibility(Thread::query()->setQuery($query), $actor, 'view')
            );
        });

        // 隐藏帖子，只有作者，或当前用户有权查看才可见。
        if (! $actor->hasPermission('threads.hidePosts')) {
            $query->where(function ($query) use ($actor) {
                $query->whereNull('posts.deleted_at')
                    // ->orWhere('posts.user_id', $actor->id) // 作者是否可见
                    ->orWhereExists(function ($query) use ($actor) {
                        $query->selectRaw('1')
                            ->from('threads')
                            ->whereColumn('threads.id', 'posts.thread_id')
                            ->where(function ($query) use ($actor) {
                                $this->events->dispatch(
                                    new ScopeModelVisibility(Thread::query()->setQuery($query), $actor, 'hidePosts')
                                );
                            });
                    });
            });
        }

        // 未通过审核的帖子
        if (! $actor->hasPermission('thread.approvePosts')) {
            $query->where(function (Builder $query) use ($actor) {
                $query->where('is_approved', Post::APPROVED)
                    ->orWhere('posts.user_id', $actor->id);
            });
        }
    }

    /**
     * @param User $actor
     * @param Post $post
     * @return bool|null
     */
    public function edit(User $actor, Post $post)
    {
        // 作者本人，或管理员才可编辑
        if ($post->user_id == $actor->id || $actor->isAdmin()) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Post $post
     * @return bool|null
     */
    public function hide(User $actor, Post $post)
    {
        return $this->edit($actor, $post);
    }
}
