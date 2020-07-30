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

namespace App\Listeners\DenyUser;

use App\Commands\Users\DeleteUserFollow;
use App\Events\DenyUsers\Saved;
use Illuminate\Contracts\Bus\Dispatcher;

class DeleteFollow
{
    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    public function handle(Saved $event)
    {
        $this->bus->dispatch(
            new DeleteUserFollow($event->actor, 0, $event->denyUser->deny_user_id)
        );
    }
}
