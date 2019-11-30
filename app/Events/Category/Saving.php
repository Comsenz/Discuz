<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Category;

use App\Models\Category;
use App\Models\User;

class Saving
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
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param Category $category
     * @param User $actor
     * @param array $data
     */
    public function __construct(Category $category, User $actor = null, array $data = [])
    {
        $this->category = $category;
        $this->actor = $actor;
        $this->data = $data;
    }
}
