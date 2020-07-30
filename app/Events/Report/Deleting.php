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

namespace App\Events\Report;

use App\Models\Report;
use App\Models\User;

class Deleting
{
    /**
     * @var Report
     */
    public $report;

    /**
     * @var User
     */
    public $actor;

    /**
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * Deleting constructor.
     *
     * @param Report $report
     * @param User|null $actor
     * @param array $data
     */
    public function __construct(Report $report, User $actor = null, array $data = [])
    {
        $this->report = $report;
        $this->actor = $actor;
        $this->data = $data;
    }
}
