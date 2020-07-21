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

namespace App\Events\Group;

use App\Models\Group;
use App\Models\User;

class Saving
{
    /**
     * The group that will be saved.
     *
     * @var Group
     */
    public $group;

    /**
     * The user who is performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes to update on the group.
     *
     * @var array
     */
    public $data;

    /**
     * @param Group $group The group that will be saved.
     * @param User $actor The user who is performing the action.
     * @param array $data The attributes to update on the group.
     */
    public function __construct(Group $group, User $actor, array $data)
    {
        $this->group = $group;
        $this->actor = $actor;
        $this->data = $data;
    }
}
