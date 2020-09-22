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

use App\Models\Category;
use App\Models\Thread;
use App\Models\User;
use App\Settings\SettingsRepository;
use Discuz\Api\Events\ScopeModelVisibility;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

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
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param Dispatcher $events
     * @param SettingsRepository $settings
     */
    public function __construct(Dispatcher $events, SettingsRepository $settings)
    {
        $this->events = $events;
        $this->settings = $settings;
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

        // 是当前分类的版主 且 拥有该权限
        if (
            $thread->category
            && ! $actor->isGuest()
            // && in_array($actor->id, explode(',', $thread->category->moderators))
            && $actor->hasPermission('category' . $thread->category->id . '.thread.' . $ability)
        ) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {
        $request = app('request');

        // 过滤不存在用户的内容
        $query->whereExists(function ($query) {
            $query->selectRaw('1')
                ->from('users')
                ->whereColumn('threads.user_id', 'users.id');
        });

        // 隐藏不允许当前用户查看的分类内容。
        if (Arr::get($request->getQueryParams(), 'filter.isSite', '') !== 'yes') {
            $query->whereNotIn('category_id', Category::getIdsWhereCannot($actor, 'viewThreads'));
        }

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

        //过滤小程序视频主题
        if (!$this->settings->get('miniprogram_video', 'wx_miniprogram') &&
            strpos(Arr::get($request->getServerParams(), 'HTTP_X_APP_PLATFORM'), 'wx_miniprogram') !== false) {
            $query->where('type', '<>', Thread::TYPE_OF_VIDEO);
        }
    }

    /**
     * @param User $actor
     * @param Thread $thread
     * @return bool|null
     */
    public function edit(User $actor, Thread $thread)
    {
        // 是作者本人且拥有编辑自己主题或回复的权限 或者 是管理员
        if (($thread->user_id == $actor->id && $actor->hasPermission('editOwnThreadOrPost')) || $actor->isAdmin()) {
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
        // 是作者本人且拥有删除自己主题或回复的权限 或者 是管理员
        if (($thread->user_id == $actor->id && $actor->hasPermission('hideOwnThreadOrPost')) || $actor->isAdmin()) {
            return true;
        }
    }
}
