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
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class CategoryPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Category::class;

    /**
     * @param User $actor
     * @param string $ability
     * @param Category $category
     * @return bool|null
     */
    public function can(User $actor, $ability, Category $category)
    {
        if ($actor->hasPermission('category.' . $ability)) {
            return true;
        }

        if (
            $category->exists
            && ! $actor->isGuest()
            // && in_array($actor->id, explode(',', $category->moderators))
            && $actor->hasPermission('category' . $category->id . '.' . $ability)
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
        $query->whereIn('id', Category::getIdsWhereCan($actor, 'viewThreads'));
    }

    /**
     * @param User $actor
     * @param Category $category
     * @return bool|null
     */
    public function viewThreads(User $actor, Category $category)
    {
        if (
            $actor->hasPermission('viewThreads')
            && $actor->hasPermission('category'.$category->id.'.viewThreads')
        ) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Category $category
     * @return bool|null
     */
    public function createThread(User $actor, Category $category)
    {
        if (
            $actor->hasPermission([
                'createThread',
                'createThreadLong',
                'createThreadVideo',
                'createThreadImage',
                'createThreadAudio',
            ], false)
            && $actor->hasPermission('category'.$category->id.'.viewThreads')
            && $actor->hasPermission('category'.$category->id.'.createThread')
        ) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Category $category
     * @return bool|null
     */
    public function replyThread(User $actor, Category $category)
    {
        if (
            $actor->hasPermission('thread.reply')
            && $actor->hasPermission('category'.$category->id.'.viewThreads')
            && $actor->hasPermission('category'.$category->id.'.replyThread')
        ) {
            return true;
        }
    }
}
