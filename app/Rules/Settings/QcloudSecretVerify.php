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
use Discuz\Validation\AbstractRule;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Ms\V20180408\Models\DescribeUserBaseInfoInstanceRequest;
use TencentCloud\Ms\V20180408\MsClient;

/**
 * 腾讯云设置 - 验证
 *
 * Class QcloudSecretVerify
 * @package App\Rules\Settings
 */
class QcloudSecretVerify extends AbstractRule
{
    private $qcloudSecretKey;

    public function __construct($qcloudSecretKey)
    {
        $this->qcloudSecretKey = $qcloudSecretKey;
    }

    /**
     * 腾讯云api设置 - 验证
     *
     * @param string $attribute
     * @param mixed $qcloudSecretId
     * @return bool|void
     * @throws TencentCloudSDKException
     * @throws TranslatorException
     */
    public function passes($attribute, $qcloudSecretId)
    {
        /**
         * 调用 TencentApi-UserUin 验证 Secret key
         */
        try {
            $cred = new Credential($qcloudSecretId, $this->qcloudSecretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint('ms.tencentcloudapi.com');

            // 签名
            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);

            $client = new MsClient($cred, '', $clientProfile);

            $req = new DescribeUserBaseInfoInstanceRequest();

            $params = '{}';
            $req->fromJsonString($params);

            $resp = $client->DescribeUserBaseInfoInstance($req);

            // Result data is string can print_r($str)
            $str = $resp->toJsonString();
        } catch (TencentCloudSDKException $e) {
            throw new TranslatorException('tencent_secret_key_error', [$e->getErrorCode()]);
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
