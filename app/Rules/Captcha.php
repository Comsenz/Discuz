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

namespace App\Rules;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Qcloud\QcloudTrait;
use Discuz\Validation\AbstractRule;

class Captcha extends AbstractRule
{
    use QcloudTrait;

    /**
     * @var int 腾讯云返回的状态码，错误时提示，以便排查
     */
    public $captchaCode;

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /** @var SettingsRepository $settings */
        $settings = app(SettingsRepository::class);

        // 未开启验证码服务时，返回 true
        if (! (bool) $settings->get('qcloud_captcha', 'qcloud')) {
            return true;
        }

        $value = (array) $value;

        // 参数数量错误时，返回 false
        if (count(array_filter($value)) !== 3) {
            return false;
        }

        $result = $this->describeCaptchaResult(...$value);

        if ($result['CaptchaCode'] === 1) {
            return true;
        } else {
            $this->captchaCode = $result['CaptchaCode'];

            return false;
        }
    }

    /**
     * @return string
     */
    public function message()
    {
        return trans('validation.wrong') . $this->captchaCode;
    }
}
