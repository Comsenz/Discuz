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

namespace App\Listeners\Thread;

use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasCategorized;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Support\Arr;

class SaveCategoryToDatabase
{
    use AssertPermissionTrait;

    /**
     * @param Saving $event
     * @throws CategoryNotFoundException
     * @throws PermissionDeniedException
     */
    public function handle(Saving $event)
    {
        $thread = $event->thread;
        $actor = $event->actor;

        $categoryId = Arr::get($event->data, 'relationships.category.data.id');

        // 如果主题尚未分类 或 接收到的分类与当前分类不一致，就修改分类
        if (! $thread->category_id || $categoryId && $thread->category_id != $categoryId) {
            if ($thread->exists) {
                $this->assertCan($actor, 'edit', $thread);
            }

            // 如果接收到可用的分类，则设置分类
            /** @var Category $category */
            if ($category = Category::query()->where('id', $categoryId)->first()) {
                $thread->raise(
                    new ThreadWasCategorized($thread, $actor, $category, $thread->category)
                );

                $thread->category_id = $category->id;
            } else {
                throw new CategoryNotFoundException;
            }

            // 是否有权在该分类下发布内容
            if ($actor->cannot('createThread', $category)) {
                throw new PermissionDeniedException;
            }
        }
    }
}
