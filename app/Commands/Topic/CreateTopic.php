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

namespace App\Commands\Topic;

use App\Censor\Censor;
use App\Models\Topic;
use App\Models\User;
use App\Validators\VoteValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class CreateTopic
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    protected $actor;

    protected $data;

    public function __construct(User $actor, $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(Dispatcher $events, VoteValidator $validation, Censor $censor)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'createThread');

        $censor->checkText(Arr::get($this->data, 'content'));

        return Topic::firstOrCreate(
            ['content' => Arr::get($this->data, 'content')],
            ['user_id'=>$this->actor->id]
        );
    }
}
