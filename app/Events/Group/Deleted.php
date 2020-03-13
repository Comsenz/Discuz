<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Group;

use App\Models\Group;
use App\Models\User;

class Deleted
{
    /**
     * @var Group
     */
    public $group;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Group $group
     * @param User $actor
     */
    public function __construct(Group $group, User $actor = null)
    {
        $this->group = $group;
        $this->actor = $actor;
    }
}
