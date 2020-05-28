<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Settings;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Encryption\Encrypter;

class ForumSettingField
{
    protected $settings;

    protected $url;

    protected $encrypter;

    /**
     * ForumSettingField constructor.
     *
     * @param SettingsRepository $settings
     * @param UrlGenerator $url
     * @param Encrypter $encrypter
     */
    public function __construct(SettingsRepository $settings, UrlGenerator $url, Encrypter $encrypter)
    {
        $this->settings = $settings;
        $this->url = $url;
        $this->encrypter = $encrypter;
    }

    /**
     * 站点地址 - 拼接
     *
     * @param $imgName
     * @return string
     */
    public function siteUrlSplicing($imgName)
    {
        if ($imgName) {
            return $this->url->to('/storage/' . $imgName);
        }

        return '';
    }

    /**
     * 字符 - 解密
     *
     * @param string $value
     * @return mixed|string
     */
    public function decrypt($value = '')
    {
        return $value ? $this->encrypter->decrypt($value) : '';
    }

    /**
     * 站点设置 - 管理员可见
     *
     * @return array
     */
    public function getSiteSettings()
    {
        return [
            'site_author_scale' => $this->settings->get('site_author_scale'), // 作者比例
            'site_master_scale' => $this->settings->get('site_master_scale'), // 站长比例
            'username_bout' => $this->settings->get('username_bout', 'default', 1), // 用户名修改次数
        ];
    }

    /**
     * 第三方登录设置 - 管理员可见
     *
     * @return array
     */
    public function getPassportSettings()
    {
        return [
            // - 微信 H5
            'offiaccount_app_id' => $this->settings->get('offiaccount_app_id', 'wx_offiaccount') ?: '',
            'offiaccount_app_secret' => $this->settings->get('offiaccount_app_secret', 'wx_offiaccount') ?: '',
            // - 微信 小程序
            'miniprogram_app_id' => $this->settings->get('miniprogram_app_id', 'wx_miniprogram') ?: '',
            'miniprogram_app_secret' => $this->settings->get('miniprogram_app_secret', 'wx_miniprogram') ?: '',
            // - 微信 开放平台
            'oplatform_app_id' => $this->settings->get('oplatform_app_id', 'wx_oplatform') ?: '',
            'oplatform_app_secret' => $this->settings->get('oplatform_app_secret', 'wx_oplatform') ?: '',
            // - 微信 PC
            'oplatform_url' =>  $this->url->route('wechat.web.user.event') ?: '',
            'oplatform_app_token' =>$this->settings->get('oplatform_app_token', 'wx_oplatform') ?: '',
            'oplatform_app_aes_key' => $this->settings->get('oplatform_app_aes_key', 'wx_oplatform') ?: '',
        ];
    }

    /**
     * 获取支付设置 - 管理员可见
     *
     * @return array
     */
    public function getPaycenterSettings()
    {
        return [
            'mch_id' => $this->settings->get('mch_id', 'wxpay'),
            'app_id' => $this->settings->get('app_id', 'wxpay'),
            'api_key' => $this->settings->get('api_key', 'wxpay'),
            'app_secret' => $this->settings->get('app_secret', 'wxpay'),
            'wxpay_mch_id' => $this->settings->get('wxpay_mch_id', 'wxpay'),
            'wxpay_app_id' => $this->settings->get('wxpay_app_id', 'wxpay'),
            'wxpay_api_key' => $this->settings->get('wxpay_api_key', 'wxpay'),
            'wxpay_app_secret' => $this->settings->get('wxpay_app_secret', 'wxpay'),
        ];
    }

    /**
     * 腾讯云设置 - 管理员可见
     *
     * @return array
     */
    public function getQCloudSettings()
    {
        $settings = [
            'qcloud_secret_id' => $this->settings->get('qcloud_secret_id', 'qcloud'),
            'qcloud_secret_key' => $this->settings->get('qcloud_secret_key', 'qcloud'),
            'qcloud_token' => $this->settings->get('qcloud_token', 'qcloud'),
            'qcloud_captcha_app_id' => $this->settings->get('qcloud_captcha_app_id', 'qcloud'),
            'qcloud_captcha_secret_key' => $this->settings->get('qcloud_captcha_secret_key', 'qcloud'),
            'qcloud_cms_image' => (bool)$this->settings->get('qcloud_cms_image', 'qcloud'),
            'qcloud_cms_text' => (bool)$this->settings->get('qcloud_cms_text', 'qcloud'),
            'qcloud_faceid_region' => (bool)$this->settings->get('qcloud_faceid_region', 'qcloud'),
            'qcloud_sms_app_id' => $this->settings->get('qcloud_sms_app_id', 'qcloud'),
            'qcloud_sms_app_key' => $this->settings->get('qcloud_sms_app_key', 'qcloud'),
            'qcloud_sms_template_id' => $this->settings->get('qcloud_sms_template_id', 'qcloud'),
            'qcloud_sms_sign' => $this->settings->get('qcloud_sms_sign', 'qcloud'),
            'qcloud_cos_bucket_name' => $this->settings->get('qcloud_cos_bucket_name', 'qcloud'),
            'qcloud_cos_bucket_area' => $this->settings->get('qcloud_cos_bucket_area', 'qcloud'),
            'qcloud_cos_cdn_url' => $this->settings->get('qcloud_cos_cdn_url', 'qcloud'),
            'qcloud_vod_transcode' => $this->settings->get('qcloud_vod_transcode', 'qcloud'),
            'qcloud_vod_cover_template' => $this->settings->get('qcloud_vod_cover_template', 'qcloud'),
            'qcloud_vod_url_key' => $this->settings->get('qcloud_vod_url_key', 'qcloud'),
            'qcloud_vod_url_expire' => $this->settings->get('qcloud_vod_url_expire', 'qcloud'),
            'qcloud_vod_taskflow_gif' => $this->settings->get('qcloud_vod_taskflow_gif', 'qcloud'),
        ];
        $settings += $this->getQCloudVod();
        return $settings;
    }

    /**
     * 提现设置 - 管理员可见
     *
     * @return array
     */
    public function getCashSettings()
    {
        return [
            'cash_interval_time' => $this->settings->get('cash_interval_time', 'cash'),
            'cash_min_sum' => $this->settings->get('cash_min_sum', 'cash') ?: '',
            'cash_max_sum' => $this->settings->get('cash_max_sum', 'cash'),
            'cash_sum_limit' => $this->settings->get('cash_sum_limit', 'cash'),
        ];
    }

    /**
     * 站点开关 - 限制
     *
     * @return array
     */
    public function getSiteClose()
    {
        return [
            'site_close_msg' => $this->settings->get('site_close_msg'),
        ];
    }

    /**
     * 付费字段 - 限制
     */
    public function getSitePayment()
    {
        return [
            'site_price' => $this->settings->get('site_price') ?: 0,
            'site_expire' => $this->settings->get('site_expire') ?: '',
        ];
    }

    public function getQCloudVod()
    {
        return [
            'qcloud_vod_ext' => $this->settings->get('qcloud_vod_ext', 'qcloud'),
            'qcloud_vod_size' => $this->settings->get('qcloud_vod_size', 'qcloud'),
            'qcloud_vod_sub_app_id' => $this->settings->get('qcloud_vod_sub_app_id', 'qcloud'),
        ];
    }

    /**
     * 水印设置
     *
     * @return array
     */
    public function getWatermarkSettings()
    {
        $watermarkImage = $this->settings->get('watermark_image', 'watermark');

        $watermarkImageUrl = $watermarkImage ? $this->url->to('/storage/' . $watermarkImage) : '';

        return [
            'watermark' => (bool) $this->settings->get('watermark', 'watermark'),
            'watermark_image' => $watermarkImageUrl,
            'position' => (int) $this->settings->get('position', 'watermark', 1),
            'horizontal_spacing' => (int) $this->settings->get('horizontal_spacing', 'watermark'),
            'vertical_spacing' => (int) $this->settings->get('vertical_spacing', 'watermark'),
        ];
    }
}
