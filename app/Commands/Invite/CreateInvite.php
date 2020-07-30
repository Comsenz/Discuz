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

namespace App\Commands\Invite;

use App\Events\Invite\Saving;
use App\Models\Invite;
use App\Models\User;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class CreateInvite
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new invitation.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor
     * @param array $data
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @return Invite
     * @throws PermissionDeniedException
     */
    public function handle(Dispatcher $events)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'createInvite');

        $invite = Invite::creation(
            Arr::get($this->data, 'attributes.group_id'),
            Invite::TYPE_ADMIN,
            Str::random(32),
            Carbon::now()->timestamp,
            Carbon::now()->addDays(7)->timestamp,
            $this->actor->id
        );

        $this->events->dispatch(
            new Saving($invite, $this->actor, $this->data)
        );

        $invite->save();

        $this->dispatchEventsFor($invite);

        return $invite;
    }
}
