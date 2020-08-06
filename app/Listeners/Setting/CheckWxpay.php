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
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckWxpay
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
        $settings = $event->settings->where('tag', 'wxpay')->pluck('value', 'key')->toArray();

        if (Arr::hasAny($settings, [
            'wxpay_close',
            'app_id',
            'mch_id',
            'api_key',
        ])) {
            // 合并原配置与新配置（新值覆盖旧值）
            $settings = array_merge((array) $this->settings->tag('wxpay'), $settings);

            $appIds = [
                $this->settings->get('miniprogram_app_id', 'wx_miniprogram'),
                $this->settings->get('offiaccount_app_id', 'wx_offiaccount')
            ];

            $this->validator->make($settings, [
                'wxpay_close' => 'nullable|boolean',
                'app_id' => ['required_if:wxpay_close,1', Rule::in($appIds)],
                'mch_id' => 'required_if:wxpay_close,1',
                'api_key' => 'required_if:wxpay_close,1',
            ], [
                'app_id.in' => trans('setting.wxpay_appid_error'),
                'app_id.required_if' => trans('setting.app_id_cannot_be_empty'),
                'mch_id.required_if' => trans('setting.mch_id_cannot_be_empty'),
                'api_key.required_if' => trans('setting.api_key_cannot_be_empty'),
            ])->validate();
        }
    }
}
