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
use Illuminate\Validation\ValidationException;

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
        $events->listen(Saving::class, [$this, 'whenThreadSaving']);
        $events->listen(Created::class, SaveVideoToDatabase::class);
    }

    /**
     * @param Saving $event
     * @throws ValidationException
     */
    public function whenThreadSaving(Saving $event)
    {
        $thread = $event->thread;

        // 视频帖 或 语音帖
        if (! $thread->exists && in_array($thread->type, [Thread::TYPE_OF_VIDEO, Thread::TYPE_OF_AUDIO])) {
            $this->validator->make(
                [
                    'switch' => (bool) $this->settings->get('qcloud_vod', 'qcloud'),
                    'file_id' => Arr::get($event->data, 'attributes.file_id', ''),
                    'file_name' => Arr::get($event->data, 'attributes.file_id', ''),
                ],
                [
                    'switch' => function ($attribute, $value, $fail) {
                        $value ?: $fail(trans('validation.qcloud_vod'));
                    },
                    'file_id' => 'required',
                    'file_name' => 'required',
                ]
            )->validate();
        }
    }
}
