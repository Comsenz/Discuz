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
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckPayAppId
{
    public $validator;

    public $settings;

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
     * @param Saving $event
     * @throws ValidationException
     */
    public function handle(Saving $event)
    {
        $settings = $event->settings->where('tag', 'wxpay')->pluck('value', 'key')->all();
        if (Arr::get($settings, 'app_id')) {
            $app_ids = [
                $this->settings->get('miniprogram_app_id', 'wx_miniprogram'),
                $this->settings->get('offiaccount_app_id', 'wx_offiaccount')
            ];

            $this->validator->make(
                $settings,
                [
                    'app_id' => [
                        'required',
                        Rule::in($app_ids),
                    ],
                ],
                [
                    'in' => trans('setting.wxpay_appid_error')
                ]
            )->validate();
        }
    }
}
