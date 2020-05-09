<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Thread;

use App\Events\Thread\Saving;
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
            // 如果接收到可用的分类，则设置分类
            if ($category = Category::query()->where('id', $categoryId)->first()) {
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
