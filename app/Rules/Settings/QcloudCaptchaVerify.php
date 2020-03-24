<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use TencentCloud\Captcha\V20190722\CaptchaClient;
use TencentCloud\Captcha\V20190722\Models\DescribeCaptchaResultRequest;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;

/**
 * 腾讯云验证码 - 验证
 *
 * Class QcloudCaptchaVerify
 * @package App\Rules\Settings
 */
class QcloudCaptchaVerify extends BaseQcloud
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    private $key;

    private $ticket;

    private $randStr;

    public function __construct($key, $ticket, $randStr)
    {
        parent::__construct();

        $this->key = $key;
        $this->ticket = $ticket;
        $this->randStr = $randStr;
    }

    /**
     * @param string $attribute
     * @param mixed $appId
     * @return bool
     */
    public function passes($attribute, $appId)
    {
        try {
            $cred = new Credential($this->qcloudSecretId, $this->qcloudSecretKey);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint('captcha.tencentcloudapi.com');

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);

            $client = new CaptchaClient($cred, '', $clientProfile);

            $req = new DescribeCaptchaResultRequest();

            $request = app('request');

            $params = [
                'CaptchaType' => 9,
                'Ticket' => $this->ticket,
                'Randstr' => $this->randStr,
                'UserIp' => ip($request->getServerParams()),
                'CaptchaAppId' => (int) $appId,
                'AppSecretKey' => $this->key,
            ];
            $req->fromJsonString(json_encode($params));

            $resp = $client->DescribeCaptchaResult($req);

            // Result data is string can print_r($str)
            $serialize = $resp->serialize();

            $bool = $this->errorMessage($serialize);
            return $bool;

        } catch (TencentCloudSDKException $e) {
            $message = $e->getErrorCode() ?: 'tencent_captcha_key_error';
            $this->message = 'tencent_captcha_' . $message;
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return trans('setting.' . $this->message);
    }

    /**
     * @param $array
     * @inheritDoc https://cloud.tencent.com/document/product/1110/36926
     */
    public function errorMessage($array)
    {
        $bool = false;

        if (array_key_exists('CaptchaCode', $array)) {
            if ($array['CaptchaCode'] == 1) {
                return true;
            }

            $document = [6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 100];
            if (in_array($array['CaptchaCode'], $document)) {
                $this->message = 'tencent_captcha_code_' . $array['CaptchaCode'];
            } else {
                $this->message = 'tencent_captcha_unknown_error';
            }

        } else {
            $this->message = 'tencent_captcha_unknown_CaptchaCode';
        }

        return $bool;
    }
}
