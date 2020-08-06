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
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

class CheckMiniprogram
{
    use EasyWechatTrait;

    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @var Validator
     */
    public $validator;

    /**
     * @param SettingsRepository $settings
     * @param Validator $validator
     */
    public function __construct(SettingsRepository $settings, Validator $validator)
    {
        $this->settings = $settings;
        $this->validator = $validator;
    }

    /**
     * @param Saving $event
     * @throws ValidationException
     */
    public function handle(Saving $event)
    {
        $settings = $event->settings->where('tag', 'wx_miniprogram')->pluck('value', 'key')->toArray();

        if (Arr::hasAny($settings, [
            'miniprogram_close',
            'miniprogram_video',
            'miniprogram_app_id',
            'miniprogram_app_secret',
        ])) {
            // 合并原配置与新配置（新值覆盖旧值）
            $settings = array_merge((array) $this->settings->tag('wx_miniprogram'), $settings);

            $this->validator->make($settings, [
                'miniprogram_close' => 'nullable|boolean',
                'miniprogram_video' => 'nullable|boolean',
                'miniprogram_app_id' => 'required_if:miniprogram_close,1',
                'miniprogram_app_secret' => 'required_if:miniprogram_close,1',
            ], [
                'miniprogram_app_id.required_if' => trans('setting.app_id_cannot_be_empty'),
                'miniprogram_app_secret.required_if' => trans('setting.app_secret_cannot_be_empty'),
            ])->validate();
        }
    }
}
