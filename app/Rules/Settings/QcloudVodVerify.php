<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Qcloud\QcloudTrait;
use TencentCloud\Common\Exception\TencentCloudSDKException;

/**
 * 腾讯云云点播 - 验证
 *
 * Class QcloudVodVerify
 * @package App\Rules\Settings
 */
class QcloudVodVerify extends BaseQcloud
{
    use QcloudTrait;

    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    private $key;

    private $ticket;

    private $randStr;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $attribute
     * @param $value
     * @return bool
     * @throws TencentCloudSDKException
     */
    public function passes($attribute, $value)
    {
        //只有开启时需要验证
        if ($value == 1) {
            if (!$this->settings->get('qcloud_vod_transcode', 'qcloud')) {
                throw new TencentCloudSDKException(500, 'tencent_vod_transcode_error');
            }

            try {
                $this->describeStorageData();
            } catch (TencentCloudSDKException $e) {
                $message = 'tencent_vod_error';
                if ($e->getCode() == 'FailedOperation.InvalidVodUser') {
                    $message = 'tencent_vod_subappid_error';
                }
                throw new TencentCloudSDKException(500, $message);
            }
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
    }

    /**
     * @param $array
     */
    public function errorMessage($array)
    {
    }
}
