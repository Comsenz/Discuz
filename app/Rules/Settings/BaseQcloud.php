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

use Discuz\Validation\AbstractRule;
use Discuz\Contracts\Setting\SettingsRepository;
use TencentCloud\Common\Exception\TencentCloudSDKException;

/**
 * 腾讯云Api -基类
 *
 * Class BaseQcloud
 * @package App\Rules\Settings
 */
class BaseQcloud extends AbstractRule
{
    protected $qcloudSecretId;

    protected $qcloudSecretKey;

    protected $settings;

    /**
     * BaseQcloud constructor.
     */
    protected function __construct()
    {
        $this->settings = app()->make(SettingsRepository::class);

        $this->qcloudSecretId =  $this->settings->get('qcloud_secret_id', 'qcloud');
        $this->qcloudSecretKey =  $this->settings->get('qcloud_secret_key', 'qcloud');

        if (blank($this->qcloudSecretId) || blank($this->qcloudSecretKey)) {
            throw new TencentCloudSDKException('tencent_secret_key_error');
        }
    }

    /**
     * 如果要开启，先判断总开关状态
     *
     * @param string $key 要操作的键
     * @param bool $setKey 要设置的值
     * @return bool
     * @throws TencentCloudSDKException
     */
    protected function currentKeyStatus($key, $setKey)
    {
        if (is_null($key)) {
            return true;
        }

        if ($setKey == 1) {
            // 如果key值要开启，先判断 总开关是否开启
            if (!$this->settings->get('qcloud_close', 'qcloud')) {
                throw new TencentCloudSDKException('tencent_qcloud_close_current');
            }
        }

        return true;
    }
}
