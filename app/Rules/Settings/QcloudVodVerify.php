<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Qcloud\QcloudTrait;
use Illuminate\Support\Arr;
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

    protected $subAppId;

    public function __construct($subAppId = '')
    {
        parent::__construct();

        $this->subAppId = $subAppId;
    }

    /**
     * @param string $attribute
     * @param $value
     * @return bool
     * @throws TencentCloudSDKException
     */
    public function passes($attribute, $value)
    {
        try {
            //开启视频开关时通过setting的值进行验证
            if ($attribute == 'qcloud_vod') {
                $value = $this->subAppId;
            }

            $this->describeStorageData($value);
        } catch (TencentCloudSDKException $e) {
            $message = 'tencent_vod_error';
            if ($e->getCode() == 'FailedOperation.InvalidVodUser') {
                $message = 'tencent_vod_subappid_error';
            }
            throw new TencentCloudSDKException($message);
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
