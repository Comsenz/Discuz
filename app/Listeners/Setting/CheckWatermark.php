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

namespace App\Listeners\Setting;

use App\Events\Setting\Saving;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckWatermark
{
    /**
     * @var Validator
     */
    public $validator;

    /**
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Saving $event
     * @throws ValidationException
     */
    public function handle(Saving $event)
    {
        $settings = $event->settings->where('tag', 'watermark')->pluck('value', 'key')->all();

        $watermark = (bool) Arr::get($settings, 'watermark');

        $this->validator->make($settings, [
            'watermark' => 'nullable|boolean',
            'position' => [Rule::requiredIf($watermark), 'integer', 'between:1,9'],
            'horizontal_spacing' => [Rule::requiredIf($watermark), 'integer', 'between:0,9999'],
            'vertical_spacing' => [Rule::requiredIf($watermark), 'integer', 'between:0,9999'],
        ])->validate();
    }
}
