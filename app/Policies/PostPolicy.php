<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: PostPolicy.php xxx 2019-10-31 19:41:00 LiuDongdong $
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
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
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
                    ->orWhere('posts.user_id', $actor->id)
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
