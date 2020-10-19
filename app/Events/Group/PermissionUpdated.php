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
use Illuminate\Support\Collection;

class PermissionUpdated
{
    /**
     * @var Group
     */
    public $group;

    /**
     * @var Collection
     */
    public $oldPermissions;

    /**
     * @var Collection
     */
    public $newPermissions;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Group $group
     * @param Collection $oldPermissions
     * @param Collection $newPermissions
     * @param User|null $actor
     */
    public function __construct(Group $group, Collection $oldPermissions, Collection $newPermissions, User $actor = null)
    {
        $this->group = $group;
        $this->oldPermissions = $oldPermissions;
        $this->newPermissions = $newPermissions;
        $this->actor = $actor;
    }
}
