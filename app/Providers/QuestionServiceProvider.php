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

namespace App\Providers;

use App\Formatter\Formatter;
use App\Listeners\Question\QuestionAttachment;
use App\Listeners\Question\QuestionListener;
use App\Models\Question;
use Discuz\Foundation\AbstractServiceProvider;

class QuestionServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }

    /**
     * @return void
     */
    public function boot()
    {
        Question::setFormatter($this->app->make(Formatter::class));

        $events = $this->app->make('events');

        $events->subscribe(QuestionListener::class);
        $events->subscribe(QuestionAttachment::class);
    }
}
