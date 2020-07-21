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

namespace App\Commands\Group;

use App\Models\User;
use App\Repositories\GroupRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteGroup
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var User
     */
    protected $actor;

    /**
     * @param int $id
     * @param User $actor
     */
    public function __construct($id, User $actor)
    {
        $this->id = $id;
        $this->actor = $actor;
    }

    /**
     * @param GroupRepository $groups
     * @param Dispatcher $events
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function handle(GroupRepository $groups, Dispatcher $events)
    {
        $this->events = $events;

        $group = $groups->findOrFail($this->id, $this->actor);

        $this->assertCan($this->actor, 'delete', $group);

        $group->delete();

        $this->dispatchEventsFor($group, $this->actor);

        return $group;
    }
}
