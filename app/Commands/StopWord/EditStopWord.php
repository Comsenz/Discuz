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
use App\Repositories\StopWordRepository;
use App\Validators\StopWordValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditStopWord
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the stop word to edit.
     *
     * @var int
     */
    public $stopWordId;

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
     * @param int $stopWordId The ID of the stop word to edit.
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new group.
     */
    public function __construct($stopWordId, User $actor, array $data)
    {
        $this->stopWordId = $stopWordId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param StopWordRepository $stopWords
     * @param StopWordValidator $validator
     * @return StopWord
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, StopWordRepository $stopWords, StopWordValidator $validator)
    {
        $this->events = $events;

        $stopWord = $stopWords->findOrFail($this->stopWordId, $this->actor);

        $this->assertCan($this->actor, 'edit', $stopWord);

        $attributes = Arr::get($this->data, 'attributes', []);

        if (isset($attributes['ugc'])) {
            $stopWord->ugc = $attributes['ugc'];
        }

        if (isset($attributes['username'])) {
            $stopWord->username = $attributes['username'];
        }

        if (isset($attributes['signature'])) {
            $stopWord->signature = $attributes['signature'];
        }

        if (isset($attributes['dialog'])) {
            $stopWord->dialog = $attributes['dialog'];
        }

        if (isset($attributes['find'])) {
            $stopWord->find = $attributes['find'];
        }

        if (isset($attributes['replacement'])) {
            $stopWord->replacement = $attributes['replacement'];
        }

        $this->events->dispatch(
            new Saving($stopWord, $this->actor, $this->data)
        );

        $validator->valid($stopWord->getDirty());

        $stopWord->save();

        $this->dispatchEventsFor($stopWord, $this->actor);

        return $stopWord;
    }
}
