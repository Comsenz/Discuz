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

namespace App\Commands\StopWord;

use App\Events\StopWord\Saving;
use App\Models\StopWord;
use App\Models\User;
use App\Validators\StopWordValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreateStopWord
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
     * The attributes of the new stop word.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new group.
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param StopWordValidator $validator
     * @return StopWord
     * @throws NotAuthenticatedException
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, StopWordValidator $validator)
    {
        $this->events = $events;

        $this->assertRegistered($this->actor);
        $this->assertCan($this->actor, 'create');

        $stopWord = StopWord::build(
            Arr::get($this->data, 'attributes.ugc'),
            Arr::get($this->data, 'attributes.username'),
            Arr::get($this->data, 'attributes.signature'),
            Arr::get($this->data, 'attributes.dialog'),
            Arr::get($this->data, 'attributes.find'),
            Arr::get($this->data, 'attributes.replacement'),
            $this->actor
        );

        $this->events->dispatch(
            new Saving($stopWord, $this->actor, $this->data)
        );

        $validator->valid($stopWord->getAttributes());

        $stopWord->save();

        $this->dispatchEventsFor($stopWord, $this->actor);

        return $stopWord;
    }
}
