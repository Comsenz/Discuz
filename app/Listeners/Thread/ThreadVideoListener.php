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

namespace App\Listeners\Thread;

use App\Events\Thread\Created;
use App\Events\Thread\Saving;
use App\Models\Thread;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Support\Arr;

class ThreadVideoListener
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @param Validator $validator
     * @param SettingsRepository $settings
     */
    public function __construct(Validator $validator, SettingsRepository $settings)
    {
        $this->validator = $validator;
        $this->settings = $settings;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Created::class, SaveVideoToDatabase::class);
    }

}
