<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
