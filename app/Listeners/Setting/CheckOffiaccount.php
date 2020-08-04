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

class CheckOffiaccount
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
            (array) $this->settings->tag('wx_offiaccount'),
            $event->settings->where('tag', 'wx_offiaccount')->pluck('value', 'key')->toArray()
        );

        $this->validator->make($settings, [
            'offiaccount_close' => [
                function ($attribute, $value, $fail) use ($settings) {
                    // 获取一次 Access token 验证配置是否正确
                    $this->getAccessToken($settings, $fail);
                },
            ],
            'offiaccount_app_id' => 'filled',
            'offiaccount_app_secret' => 'filled',
        ])->validate();
    }

    /**
     * @param  array $settings
     * @param  \Closure $fail
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getAccessToken($settings, $fail)
    {
        try {
            $this->offiaccount([
                'app_id' => Arr::get($settings, 'offiaccount_app_id'),
                'secret' => Arr::get($settings, 'offiaccount_app_secret'),
            ])->access_token->getToken();
        } catch (\EasyWeChat\Kernel\Exceptions\HttpException $e) {
            $fail(implode(' - ', $e->formattedResponse));
        }
    }
}
