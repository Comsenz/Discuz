<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Category;

use App\Events\Category\CategoryRefreshCount;
use Illuminate\Contracts\Events\Dispatcher;

class CategoryListener
{
    public function subscribe(Dispatcher $events)
    {
        // 刷新分类数
        $events->listen(CategoryRefreshCount::class, [$this, 'refreshCount']);
    }

    public function refreshCount()
    {
        dd(889);
    }
}
