<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\GroupPermission;

use App\Models\GroupPermission;

class Created
{
    /**
     * @var GroupPermission
     */
    public $groupPermission;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param GroupPermission $groupPermission
     * @param User   $actor
     */
    public function __construct(GroupPermission $groupPermission, $actor = null)
    {
        $this->groupPermission = $groupPermission;
        $this->actor = $actor;
    }
}
