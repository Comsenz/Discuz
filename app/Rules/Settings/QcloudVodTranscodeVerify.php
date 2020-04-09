<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Qcloud\QcloudTrait;
use TencentCloud\Common\Exception\TencentCloudSDKException;

/**
 * 腾讯云云点播转码模板 - 验证
 *
 * Class QcloudVodTranscodeVerify
 * @package App\Rules\Settings
 */
class QcloudVodTranscodeVerify extends BaseQcloud
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

    protected $transcode;

    public function __construct($transcode = '')
    {
        parent::__construct();

        $this->transcode = $transcode;
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
                $value = $this->transcode;
            }

            $res = $this->DescribeTranscodeTemplates($value);
        } catch (TencentCloudSDKException $e) {
            throw new TencentCloudSDKException('qcloud_vod_'.$e->getErrorCode());
        }
        if ($res->TotalCount == 0) {
            throw new TencentCloudSDKException('tencent_vod_transcode_error');
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
