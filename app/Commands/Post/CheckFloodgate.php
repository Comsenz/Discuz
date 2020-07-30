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

namespace App\Commands\Post;

use App\Models\Post;
use App\Models\User;
use DateTime;

class CheckFloodgate
{
    /**
     * @var User
     */
    public $actor;

    public function __construct(User $actor)
    {
        $this->actor = $actor;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        if ($this->isFlooding($this->actor)) {
            throw new \Exception('too_many_requests');
        }
    }

    /**
     * @param User $actor
     * @return bool
     * @throws \Exception
     */
    public function isFlooding(User $actor): bool
    {
        return Post::where('user_id', $actor->id)->where('created_at', '>=', new DateTime('-10 seconds'))->exists();
    }
}
