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

use App\Events\Setting\Saved;
use Discuz\Contracts\Setting\SettingsRepository;

class ClearDisabledSettings
{
    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @param SettingsRepository $settings
     */
    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Saved $event
     */
    public function handle(Saved $event)
    {
        // 关闭验证码时 关闭注册验证码
        if (! $this->settings->get('qcloud_captcha', 'qcloud')) {
            $this->settings->set('register_captcha', '0');
        }

        // 关闭公众号配置时 关闭 PC 微信扫码登录
        if (! $this->settings->get('offiaccount_close', 'wx_offiaccount')) {
            $this->settings->set('oplatform_close', '0', 'wx_oplatform');
        }
    }
}
