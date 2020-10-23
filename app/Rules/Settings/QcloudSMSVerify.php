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
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20190711\Models\SmsPackagesStatisticsRequest;
use TencentCloud\Sms\V20190711\SmsClient;

/**
 * 腾讯云设置 - 短信验证
 *
 * Class QcloudSMSVerify
 * @package App\Rules\Settings
 */
class QcloudSMSVerify extends BaseQcloud
{
    private $qcloudSmsAppId;

    protected $qcloudSecretId;

    protected $qcloudSecretKey;

    /**
     * QcloudSMSVerify constructor.
     * @param null $qcloudSmsAppId
     * @throws TencentCloudSDKException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct($qcloudSmsAppId = null)
    {
        parent::__construct();

        $this->qcloudSmsAppId = $qcloudSmsAppId; // 不能为空

        $settings = app()->make(SettingsRepository::class);

        if (is_null($this->qcloudSmsAppId)) {
            // 执行开启开关
            $this->qcloudSmsAppId = $settings->get('qcloud_sms_app_id', 'qcloud');
        }
    }

    /**
     * 套餐包信息统计 - 验证
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws TencentCloudSDKException
     * @throws TranslatorException
     */
    public function passes($attribute, $value)
    {
        // 判断总开关
        $this->currentKeyStatus($attribute, $value);

        // 验证短信Api
        try {
            $cred = new Credential($this->qcloudSecretId, $this->qcloudSecretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint('sms.tencentcloudapi.com');

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new SmsClient($cred, 'ap-beijing', $clientProfile);

            $req = new SmsPackagesStatisticsRequest();

            $build = [
                'SmsSdkAppid' => $this->qcloudSmsAppId,
                'Limit' => 1,
                'Offset' => 0,
            ];

            $params = json_encode($build);
            $req->fromJsonString($params);

            $resp = $client->SmsPackagesStatistics($req);

            // Result data is string can print_r($str)
            $str = $resp->toJsonString();
        } catch (TencentCloudSDKException $e) {
            throw new TranslatorException('tencent_qcloud_sms_app_error', [$e->getErrorCode()]);
        }

        return true;
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
