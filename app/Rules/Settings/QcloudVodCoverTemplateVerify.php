<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Qcloud\QcloudTrait;
use Exception;
use TencentCloud\Common\Exception\TencentCloudSDKException;

/**
 * 腾讯云封面图模板 - 验证
 *
 * Class QcloudVodCoverTemplateVerify
 * @package App\Rules\Settings
 */
class QcloudVodCoverTemplateVerify extends BaseQcloud
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
        try {
            $res = $this->describeSnapshotByTimeOffsetTemplates($value);
        } catch (TencentCloudSDKException $e) {
            throw new TencentCloudSDKException('qcloud_vod_'.$e->getErrorCode());
        }
        if ($res->TotalCount == 0) {
            throw new TencentCloudSDKException('qcloud_vod_cover_template_not_found');
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
