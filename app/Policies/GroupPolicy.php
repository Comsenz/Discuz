<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;

class GroupPolicy extends AbstractPolicy
{
    protected $model = Group::class;

    /**
     * @param User $actor
     * @param string $ability
     * @return bool|null
     */
    public function can(User $actor, $ability)
    {
        if ($actor->hasPermission('group.' . $ability)) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Group $group
     * @return bool
     */
    public function delete(User $actor, Group $group)
    {
        // 禁止删除系统用户组
        $groups = [
            Group::ADMINISTRATOR_ID,
            Group::BAN_ID,
            Group::UNPAID,
            Group::GUEST_ID,
            Group::MEMBER_ID,
        ];

        return ! in_array($group->id, $groups);
    }
}
