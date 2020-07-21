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

namespace App\Validators;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\AbstractValidator;
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Validation\Factory;

class ThreadValidator extends AbstractValidator
{
    use QcloudTrait;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * 腾讯云验证码开关。
     *
     * @var bool
     */
    protected $qCloudCaptchaSwitch = false;

    /**
     * 获取相关设置
     *
     * @param Factory $validator
     * @param SettingsRepository $settings
     */
    public function __construct(Factory $validator, SettingsRepository $settings)
    {
        parent::__construct($validator);

        $this->settings = $settings;

        // 获取后台设置的腾讯云验证码开关
        $this->qCloudCaptchaSwitch = (bool)$settings->get('qcloud_captcha', 'qcloud');
    }

    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        $rules = [
            'title' => 'required|max:80',
            'price' => [
                'sometimes',
                'regex:/^(0|[1-9]\d{0,5})(\.\d{1,2})?$/',   // decimal 10,2
            ],
        ];

        // 当腾讯云验证码开启时，且数据中有 captcha 时，检查验证码
        if ($this->qCloudCaptchaSwitch) {
            $rules['captcha'] = [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (count(array_filter($value)) == 3) {
                        $result = $this->describeCaptchaResult(...$value);

                        if ($result['CaptchaCode'] != 1) {
                            $fail(trans('validation.wrong') . "({$result['CaptchaCode']})");
                        }
                    } else {
                        $fail(trans('validation.wrong'));
                    }
                },
            ];
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessages()
    {
        return [];
    }
}
