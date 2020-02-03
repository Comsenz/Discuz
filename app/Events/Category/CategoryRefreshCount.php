<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
     * 原分类id
     *
     * @var
     */
    public $original_id;

    /**
     * @param $original_id
     * @param Category $category
     */
    public function __construct(Category $category, string $original_id)
    {
        $this->category = $category;
        $this->original_id = $original_id;
    }
}
