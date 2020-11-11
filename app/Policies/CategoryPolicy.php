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
        if (in_array($ability, Category::$categoryPermissions)) {
            // 分类下设置的其他权限
            $switchIsTurnOn = $actor->hasPermission('switch.' . $ability);
            $hasGlobalPermission = $actor->hasPermission($ability);
            $hasCategoryPermission = $actor->hasPermission('category' . $category->id . '.' . $ability);

            if ($switchIsTurnOn && ($hasGlobalPermission || $hasCategoryPermission)) {
                return true;
            }
        } else {
            // 对分类的操作权限
            if ($actor->hasPermission('category.' . $ability)) {
                return true;
            }
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
}
