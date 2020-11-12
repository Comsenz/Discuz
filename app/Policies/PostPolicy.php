<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
                                $query
                                    ->whereRaw('1=0')
                                    ->orWhere(function ($query) use ($actor) {
                                        $this->events->dispatch(
                                            new ScopeModelVisibility(Thread::query()->setQuery($query), $actor, 'hidePosts')
                                        );
                                    });
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
        // 首帖按主题权限走
        if ($post->is_first) {
            return $actor->can('edit', $post->thread);
        }

        // 是作者本人且拥有编辑自己主题或回复的权限
        if ($post->user_id == $actor->id && $actor->can('editOwnThreadOrPost', $post->thread)) {
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
        // 首帖按主题权限走
        if ($post->is_first) {
            return $actor->can('hide', $post->thread);
        }

        // 是作者本人且拥有删除自己主题或回复的权限
        if ($post->user_id == $actor->id && $actor->can('hideOwnThreadOrPost', $post->thread)) {
            return true;
        }
    }
}
