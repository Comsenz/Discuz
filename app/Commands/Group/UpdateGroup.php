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

use App\Events\Group\Saving;
use App\Repositories\GroupRepository;
use App\Validators\GroupValidator;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class UpdateGroup
{
    use AssertPermissionTrait;

    protected $id;

    protected $actor;

    protected $data;

    protected $groups;

    protected $validator;

    protected $event;

    public function __construct($id, $actor, $data)
    {
        $this->id = $id;
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(GroupRepository $groups, GroupValidator $validator, Dispatcher $event)
    {
        $this->groups = $groups;
        $this->validator = $validator;
        $this->event = $event;
        return call_user_func([$this, '__invoke']);
    }

    /**
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function __invoke()
    {
        $group = $this->groups->findOrFail($this->id, $this->actor);

        $this->assertCan($this->actor, 'edit', $group);

        if (Arr::has($this->data, 'attributes.name')) {
            $group->name = Arr::get($this->data, 'attributes.name', '');
        }

        if (Arr::has($this->data, 'attributes.type')) {
            $group->type = Arr::get($this->data, 'attributes.type', '');
        }

        if (Arr::has($this->data, 'attributes.color')) {
            $group->color = Arr::get($this->data, 'attributes.color', '');
        }

        if (Arr::has($this->data, 'attributes.icon')) {
            $group->icon = Arr::get($this->data, 'attributes.icon', '');
        }

        if (Arr::has($this->data, 'attributes.isDisplay')) {
            $group->is_display = (bool) Arr::get($this->data, 'attributes.isDisplay');
        }

        if (Arr::has($this->data, 'attributes.is_paid')) {
            $group->is_paid = (int) Arr::get($this->data, 'attributes.is_paid');
            if ($group->is_paid) {
                $group->fee = (float) Arr::get($this->data, 'attributes.fee');
                $group->days = Arr::get($this->data, 'attributes.days');
            }
        }

        if (Arr::has($this->data, 'attributes.scale')) {
            $group->scale = (float) Arr::get($this->data, 'attributes.scale');
        }

        if (Arr::has($this->data, 'attributes.is_subordinate')) {
            $group->is_subordinate = (bool) Arr::get($this->data, 'attributes.is_subordinate');
        }

        if (Arr::has($this->data, 'attributes.is_commission')) {
            $group->is_commission = (bool) Arr::get($this->data, 'attributes.is_commission');
        }

        $this->validator->valid($group->getDirty());

        $this->event->dispatch(
            new Saving($group, $this->actor, $this->data)
        );

        $group->save();

        return $group;
    }
}
