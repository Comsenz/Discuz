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

use App\Events\Group\Created;
use App\Events\Group\Saving;
use App\Models\Group;
use App\Models\User;
use App\Validators\GroupValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class CreateGroup
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    protected $actor;

    /**
     * The attributes of the new group.
     *
     * @var array
     */
    protected $data;

    protected $validator;

    /**
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new group.
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(Dispatcher $events, GroupValidator $validator)
    {
        $this->events = $events;
        $this->validator = $validator;

        return call_user_func([$this, '__invoke']);
    }

    /**
     * @return Group
     * @throws PermissionDeniedException
     */
    public function __invoke()
    {
        $this->assertCan($this->actor, 'create');

        $attributes = Arr::get($this->data, 'attributes', []);

        $group = new Group();

        $group->name = Arr::get($attributes, 'name');
        $group->type = Arr::get($attributes, 'type', '');
        $group->color = Arr::get($attributes, 'color', '');
        $group->icon = Arr::get($attributes, 'icon', '');
        $group->is_display = (bool) Arr::get($attributes, 'isDisplay');
        $group->is_paid = (int) Arr::get($attributes, 'is_paid');

        if ($group->is_paid) {
            $fee = (float) Arr::get($attributes, 'fee');
            $group->fee = sprintf('%.2f', $fee);
        }

        if ($group->is_paid) {
            $group->days = Arr::get($attributes, 'days');
        }

        $group->raise(new Created($group));

        $this->events->dispatch(
            new Saving($group, $this->actor, $this->data)
        );

        $this->validator->valid($group->getAttributes());

        $group->save();

        $this->dispatchEventsFor($group, $this->actor);
        return $group;
    }
}
