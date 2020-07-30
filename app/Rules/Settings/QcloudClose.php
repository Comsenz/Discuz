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

use App\Exceptions\TranslatorException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Qcloud\QcloudTrait;
use TencentCloud\Common\Exception\TencentCloudSDKException;

/**
 * 腾讯云关闭所有Api
 */
class QcloudClose extends BaseQcloud
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    /**
     * 联动值：
     * qcloud_cms_image 云 api 图片安全开关
     * qcloud_cms_text 云 api 内容安全开关
     * qcloud_sms 短信开关
     * qcloud_faceid 实名认证 1开启 0关闭
     * qcloud_cos 对象储存 1开启 0关闭
     * qcloud_vod 视频 1开启 0关闭
     * qcloud_captcha 验证码 1开启 0关闭
     *
     * @var string[]
     */
    protected $linkage = [
        'qcloud_cms_image',
        'qcloud_cms_text',
        'qcloud_sms',
        'qcloud_faceid',
        'qcloud_cos',
        'qcloud_vod',
        'qcloud_captcha',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function passes($attribute, $value)
    {
        if ($value) {
            return true;
        }

        /**
         * 当云API点击“关闭”时，基于云API的腾讯云服务都应该相应关闭
         */
        $settings = app()->make(SettingsRepository::class);
        foreach ($this->linkage as $item) {
            if ($settings->get($item, 'qcloud')) {
                $settings->set($item, 0, 'qcloud');
            }
        }

        return true;
    }
}
