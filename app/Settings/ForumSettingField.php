<?php

namespace App\Settings;

use Discuz\Contracts\Setting\SettingsRepository;
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
            'site_close_msg' => $this->settings->get('site_close_msg'),
            'site_install' => $this->settings->get('site_install'),
        ];
    }

    /**
     * 第三方登陆设置 - 管理员可见
     *
     * @return array
     */
    public function getPassportSettings()
    {
        return [
            // - 微信 h5
            'offiaccount_close' => $this->settings->get('offiaccount_close', 'wx_offiaccount'),
            'offiaccount_app_id' => $this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'offiaccount_app_secret' => $this->settings->get('offiaccount_app_secret', 'wx_offiaccount'),
            // - 微信小程序
            'miniprogram_close' => $this->settings->get('miniprogram_close', 'wx_miniprogram'),
            'miniprogram_app_id' => $this->settings->get('miniprogram_app_id', 'wx_miniprogram'),
            'miniprogram_app_secret' => $this->settings->get('miniprogram_app_secret', 'wx_miniprogram'),
            // - 微信 pc
            'oplatform_close' => $this->settings->get('oplatform_close', 'wx_oplatform'),
            'oplatform_app_id' => $this->settings->get('oplatform_app_id', 'wx_oplatform'),
            'oplatform_app_secret' => $this->settings->get('oplatform_app_secret', 'wx_oplatform'),
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
            'wxpay_close' => $this->settings->get('wxpay_close', 'wxpay'),
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
        return [
            'qcloud_close' => (bool)$this->settings->get('qcloud_close', 'qcloud'),
            'qcloud_app_id' => $this->settings->get('qcloud_app_id', 'qcloud'),
            'qcloud_secret_id' => $this->settings->get('qcloud_secret_id', 'qcloud'),
            'qcloud_secret_key' => $this->settings->get('qcloud_secret_key', 'qcloud'),
            'qcloud_token' => $this->settings->get('qcloud_token', 'qcloud'),
            'qcloud_cms_image' => (bool)$this->settings->get('qcloud_cms_image', 'qcloud'),
            'qcloud_cms_text' => (bool)$this->settings->get('qcloud_cms_text', 'qcloud'),
            'qcloud_faceid' => (bool)$this->settings->get('qcloud_faceid', 'qcloud'),
            'qcloud_faceid_region' => (bool)$this->settings->get('qcloud_faceid_region', 'qcloud'),
            'qcloud_sms_app_id' => $this->settings->get('qcloud_sms_app_id', 'qcloud'),
            'qcloud_sms_app_key' => $this->settings->get('qcloud_sms_app_key', 'qcloud'),
            'qcloud_sms_template_id' => $this->settings->get('qcloud_sms_template_id', 'qcloud'),
            'qcloud_sms_sign' => $this->settings->get('qcloud_sms_sign', 'qcloud'),
            'qcloud_cos_bucket_name' => $this->settings->get('qcloud_cos_bucket_name', 'qcloud'),
            'qcloud_cos_bucket_area' => $this->settings->get('qcloud_cos_bucket_area', 'qcloud'),
            'qcloud_ci_url' => $this->settings->get('qcloud_ci_url', 'qcloud'),
            'qcloud_cos' => (bool)$this->settings->get('qcloud_cos', 'qcloud'),
        ];
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
            'cash_rate' => $this->settings->get('cash_rate', 'cash'),
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
            'site_price' => $this->settings->get('site_price'),
            'site_expire' => $this->settings->get('site_expire'),
        ];
    }
}
