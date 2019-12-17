<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Filesystem\Factory;
use Discuz\Http\UrlGenerator;

class ForumSettingSerializer extends AbstractSerializer
{
    protected $type = 'forums';

    protected $settings;

    protected $url;

    protected $encrypter;

    public function __construct(SettingsRepository $settings, UrlGenerator $url, Encrypter $encrypter)
    {
        $this->settings = $settings;
        $this->url = $url;
        $this->encrypter = $encrypter;
    }

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        $attributes = [
            'siteMode' => $this->settings->get('site_mode'), //pay public
            'logo' => $this->logo($this->settings->get('logo')),
            'siteName' => $this->settings->get('site_name'),
            'siteIntroduction' => $this->settings->get('site_introduction'),
            'siteStat' => $this->settings->get('site_stat'),
            'siteRecord' => $this->settings->get('site_record'),
            'sitePrice' => $this->settings->get('site_price'),
            'siteExpire' => $this->settings->get('site_expire'),
            'siteAuthorScale' => $this->settings->get('site_author_scale'),
            'siteMasterScale' => $this->settings->get('site_master_scale'),
            'siteInstall' => $this->settings->get('site_install'),
            'threads' => Thread::count(),
            'members' => User::count(),
            'siteAuthor' => User::where('id', $this->settings->get('site_author'))->first(['id', 'username']),
            'passwordLength' => (int)$this->settings->get('password_length'),
            'passwordStrength' => $this->settings->get('password_strength'),
            'allowRegister' => (bool)$this->settings->get('allow_register'),
            'siteClose' => (bool)$this->settings->get('site_close'),
            'siteCloseMsg' => $this->settings->get('site_close_msg'),
            'supportImgExt' => $this->settings->get('support_img_ext'),
            'supportFileExt' => $this->settings->get('support_file_ext'),
            'supportMaxSize' => (int)$this->settings->get('support_max_size', 'default', ini_get('upload_max_filesize')),

            // 支付设置
            'wxpay_close' => (bool) $this->settings->get('wxpay_close', 'wxpay'),

            // 第三方登陆设置
            'passport' => [
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
            ],

            // 腾讯云设置
            'qcloud' => [
                'qcloud_close' => (bool)$this->settings->get('qcloud_close', 'qcloud'),
                'qcloud_app_id' => $this->settings->get('qcloud_app_id', 'qcloud'),
                'qcloud_secret_id' => $this->settings->get('qcloud_secret_id', 'qcloud'),
                'qcloud_secret_key' => $this->settings->get('qcloud_secret_key', 'qcloud'),
                'qcloud_token' => $this->settings->get('qcloud_token', 'qcloud'),
                'qcloud_cms_image' => (bool)$this->settings->get('qcloud_cms_image', 'qcloud'),
                'qcloud_cms_text' => (bool)$this->settings->get('qcloud_cms_text', 'qcloud'),
                'qcloud_sms' => (bool)$this->settings->get('qcloud_sms', 'qcloud'),
                'qcloud_sms_app_id' => $this->settings->get('qcloud_sms_app_id', 'qcloud'),
                'qcloud_sms_app_key' => $this->settings->get('qcloud_sms_app_key', 'qcloud'),
                'qcloud_sms_template_id' => $this->settings->get('qcloud_sms_template_id', 'qcloud'),
                'qcloud_sms_sign' => $this->settings->get('qcloud_sms_sign', 'qcloud'),
            ],

            // 提现设置
            'setcash' => [
                'cash_interval_time' => $this->settings->get('cash_interval_time', 'cash'),
                'cash_rate' => $this->settings->get('cash_rate', 'cash'),
                'cash_min_sum' => $this->settings->get('cash_min_sum', 'cash') ?: "",
                'cash_max_sum' => $this->settings->get('cash_max_sum', 'cash'),
                'cash_sum_limit' => $this->settings->get('cash_sum_limit', 'cash'),
            ],
        ];

        if ($this->actor->exists) {
            $attributes['user'] = [
                'groups' => $this->actor->groups,
                'registerTime' => $this->formatDate($this->actor->created_at),
            ];
        }

        return $attributes;
    }

    public function getId($model)
    {
        return 1;
    }

    protected function users($model)
    {
        return $this->hasMany($model, UserSerializer::class);
    }

    private function logo($logo)
    {
        if($logo) {
            return $this->url->to('/storage/' . $logo);
        }
        return '';
    }

    private function decrypt($value = '')
    {
        return $value ? $this->encrypter->decrypt($value) : '';
    }
}
