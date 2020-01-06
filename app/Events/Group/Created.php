<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Group;

use App\Models\Group;
use App\Models\User;

class Created
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
     * @var array
     */
    public $data;

    /**
     * @param Group $group
     * @param User $actor
     * @param array $data
     */
    public function __construct(Group $group, User $actor = null, array $data = [])
    {
        $this->group = $group;
        $this->actor = $actor;
        $this->data = $data;
    }
}
