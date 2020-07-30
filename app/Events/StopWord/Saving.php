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

namespace App\Events\StopWord;

use App\Models\StopWord;
use App\Models\User;

class Saving
{
    /**
     * @var StopWord
     */
    public $stopWord;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var array
     */
    public $data;

    /**
     * Saving constructor.
     *
     * @param StopWord $stopWord
     * @param User $actor
     * @param array $data
     */
    public function __construct(StopWord $stopWord, User $actor, array $data)
    {
        $this->stopWord = $stopWord;
        $this->actor = $actor;
        $this->data = $data;
    }
}
