<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

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
            throw new TencentCloudSDKException(500, 'tencent_secret_key_error');
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
