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

namespace App\Events\Attachment;

use App\Models\Attachment;
use App\Models\User;

class Deleting
{
    /**
     * The attachment that is going to be deleted.
     *
     * @var Attachment
     */
    public $attachment;

    /**
     * The user who is performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * Any user input associated with the command.
     *
     * @var array
     */
    public $data;

    /**
     * @param Attachment $attachment
     * @param User $actor
     * @param array $data
     */
    public function __construct(Attachment $attachment, User $actor, array $data = [])
    {
        $this->attachment = $attachment;
        $this->actor = $actor;
        $this->data = $data;
    }
}
