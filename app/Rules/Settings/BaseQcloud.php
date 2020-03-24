<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
            throw new TencentCloudSDKException(500, 'tencent_secret_key_error');
        }
    }
}
