<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Category;

use App\Models\Category;
use App\Models\User;

class Created
{
    /**
     * @var Category
     */
    public $category;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Category $category
     * @param User $actor
     */
    public function __construct(Category $category, $actor = null)
    {
        $this->category = $category;
        $this->actor = $actor;
    }
}
