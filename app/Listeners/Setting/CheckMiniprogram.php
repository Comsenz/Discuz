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
        // 合并原配置与新配置（新值覆盖旧值）
        $settings = array_merge(
            $this->settings->tag('wx_miniprogram'),
            $event->settings->where('tag', 'wx_miniprogram')->pluck('value', 'key')->all()
        );

        $this->validator->make($settings, [
            'miniprogram_close' => 'nullable|boolean',
            'miniprogram_app_id' => 'filled',
            'miniprogram_app_secret' => 'filled',
            'miniprogram_video' => 'nullable|boolean',
        ])->validate();
    }
}
