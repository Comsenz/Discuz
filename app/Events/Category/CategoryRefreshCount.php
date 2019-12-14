<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Category;

use App\Models\Category;

class CategoryRefreshCount
{
    /**
     * @var Category
     */
    public $category;

    /**
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
}
