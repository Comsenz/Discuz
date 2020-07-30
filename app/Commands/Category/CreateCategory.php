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

namespace App\Commands\Category;

use App\Events\Category\Saving;
use App\Models\Category;
use App\Models\User;
use App\Validators\CategoryValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreateCategory
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
     * The attributes of the new category.
     *
     * @var array
     */
    public $data;

    /**
     * The IP address of the actor.
     *
     * @var string
     */
    public $ip;

    /**
     * @param User $actor
     * @param array $data
     * @param string $ip
     */
    public function __construct(User $actor, array $data, string $ip)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param Dispatcher $events
     * @param CategoryValidator $validator
     * @return Category
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, CategoryValidator $validator)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'createCategory');

        $category = Category::build(
            Arr::get($this->data, 'attributes.name'),
            Arr::get($this->data, 'attributes.description'),
            (int) Arr::get($this->data, 'attributes.sort', 0),
            Arr::get($this->data, 'attributes.icon', ''),
            $this->ip
        );

        $this->events->dispatch(
            new Saving($category, $this->actor, $this->data)
        );

        $validator->valid($category->getAttributes());

        $category->save();

        $this->dispatchEventsFor($category);

        return $category;
    }
}
