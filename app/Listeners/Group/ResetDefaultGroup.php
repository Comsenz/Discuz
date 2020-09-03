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

namespace App\Listeners\Group;

use App\Events\Group\Deleted;
use App\Models\Group;

class ResetDefaultGroup
{
    public function handle(Deleted $event)
    {
        // 如果被删除的是默认用户组，将默认用户组还原为 member group
        if ($event->group->default) {
            /** @var Group $group */
            $group = Group::query()->find(Group::MEMBER_ID);

            $group->default = true;

            $group->save();

            Group::query()->where('id', '<>', Group::MEMBER_ID)->update(['default' => 0]);
        }
    }
}
