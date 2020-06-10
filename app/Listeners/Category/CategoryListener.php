<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Category;

use App\Events\Category\CategoryRefreshCount;
use App\Events\Category\Created;
use App\Models\Category;
use App\Models\Group;
use App\Models\GroupPermission;
use Illuminate\Contracts\Events\Dispatcher;

class CategoryListener
{
    public function subscribe(Dispatcher $events)
    {
        // 添加分类时，设置分类下的权限
        $events->listen(Created::class, [$this, 'whenCategoryCreated']);

        // 刷新分类数
        $events->listen(CategoryRefreshCount::class, [$this, 'refreshCount']);
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

        GroupPermission::query()->insert($permissions);
    }

    /**
     * @param CategoryRefreshCount $event
     */
    public function refreshCount(CategoryRefreshCount $event)
    {
        if ($event->original_id != $event->category->id) {
            $originalCate = Category::where('id', $event->original_id)->first();
            $originalCate->refreshThreadCount();
            $originalCate->save();
        }

        $event->category->refreshThreadCount();
        $event->category->save();
    }
}
