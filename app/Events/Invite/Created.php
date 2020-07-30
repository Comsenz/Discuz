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

namespace App\Events\Invite;

use App\Models\Invite;

class Created
{
    /**
     * @var Invite
     */
    public $invite;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Invite $invite
     * @param User   $actor
     */
    public function __construct(Invite $invite, $actor = null)
    {
        $this->invite = $invite;
        $this->actor = $actor;
    }
}
