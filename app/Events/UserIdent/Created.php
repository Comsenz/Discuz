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

namespace App\Events\UserIdent;

use App\Models\UserIdent;

class Created
{
    /**
     * @var UserIdent
     */
    public $UserIdent;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param User $user
     * @param User   $actor
     */
    public function __construct(UserIdent $user, $actor = null)
    {
        $this->user = $user;
        $this->actor = $actor;
    }
}
