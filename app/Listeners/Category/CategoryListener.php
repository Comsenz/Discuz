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

namespace App\Listeners\Category;

use App\Events\Category\Created;
use App\Models\Group;
use App\Models\Permission;
use Illuminate\Contracts\Events\Dispatcher;

class CategoryListener
{
    public function subscribe(Dispatcher $events)
    {
        // 添加分类时，设置分类下的权限
        $events->listen(Created::class, [$this, 'whenCategoryCreated']);
    }

    /**
     * @param Created $event
     */
    public function whenCategoryCreated(Created $event)
    {
        $category = $event->category;

        $groupIds = Group::query()->where('id', '>=', Group::MEMBER_ID)->pluck('id');

        $permissions = $groupIds->reduce(function ($carry, $groupId) use ($category) {
            return array_merge($carry, [
                [
                    'group_id' => $groupId,
                    'permission' => "category{$category->id}.viewThreads",
                ],
                [
                    'group_id' => $groupId,
                    'permission' => "category{$category->id}.createThread",
                ],
                [
                    'group_id' => $groupId,
                    'permission' => "category{$category->id}.replyThread",
                ],
            ]);
        }, [
            [
                'group_id' => Group::GUEST_ID,
                'permission' => "category{$category->id}.viewThreads",
            ],
        ]);

        Permission::query()->insert($permissions);
    }
}
