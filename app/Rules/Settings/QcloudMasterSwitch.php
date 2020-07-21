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

namespace App\Rules\Settings;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Validation\AbstractRule;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20190711\Models\SmsPackagesStatisticsRequest;
use TencentCloud\Sms\V20190711\SmsClient;

/**
 * 腾讯云总开关 - 验证
 */
class QcloudMasterSwitch extends BaseQcloud
{
    /**
     * QcloudMasterSwitch constructor.
     * @throws TencentCloudSDKException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws TencentCloudSDKException
     */
    public function passes($attribute, $value)
    {
        return $this->currentKeyStatus($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        // TODO: Implement message() method.
    }
}
