<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Deleted.php xxx 2019-11-30 17:46:00 LiuDongdong $
 */

namespace App\Events\Category;

use App\Models\Category;
use App\Models\User;

class Deleted
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
