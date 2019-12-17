<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Category;

use App\Models\Category;
use App\Events\Category\CategoryRefreshCount;
use Illuminate\Contracts\Events\Dispatcher;

class CategoryListener
{
    public function subscribe(Dispatcher $events)
    {
        // 刷新分类数
        $events->listen(CategoryRefreshCount::class, [$this, 'refreshCount']);
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
