<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Thread;
use App\Models\User;
use Carbon\Carbon;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Encryption\Encrypter;
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
        $logo = $this->logo($this->settings->get('logo'));

        $attributes = [
            // 基础信息
            'siteName' => $this->settings->get('site_name'),
            'logo' => $logo ? $logo . '?' . Carbon::now()->timestamp : '',
            'siteIntroduction' => $this->settings->get('site_introduction'),
            'siteStat' => $this->settings->get('site_stat'),
            'siteRecord' => $this->settings->get('site_record'),
            'siteInstall' => $this->settings->get('site_install'),
            'threads' => Thread::where('is_approved', Thread::APPROVED)->whereNull('deleted_at')->count(),
            'members' => User::where('status', 0)->count(),
            'siteAuthor' => User::where('id', $this->settings->get('site_author'))->first(['id', 'username']),
            'passwordLength' => (int)$this->settings->get('password_length'),
            'passwordStrength' => $this->settings->get('password_strength'),
            'allowRegister' => (bool)$this->settings->get('allow_register'),

            // 图片、附件上传设置
            'supportImgExt' => $this->settings->get('support_img_ext'),
            'supportFileExt' => $this->settings->get('support_file_ext'),
            'supportMaxSize' => (int)$this->settings->get('support_max_size', 'default', ini_get('upload_max_filesize')),

            // 权限
            'canUploadAttachments' => $this->actor->can('attachment.create.0'),
            'canUploadImages' => $this->actor->can('attachment.create.1'),
            'canCreateThread' => $this->actor->can('createThread'),
            'canViewThreads' => $this->actor->can('viewThreads'),
            'canBatchEditThreads' => $this->actor->can('thread.batchEdit'),
            'canViewUserList' => $this->actor->can('viewUserList'),
            'canEditUserGroup' => $this->actor->can('user.edit.group'),
            'canCreateInvite' => $this->actor->can('createInvite'),

            // 支付设置
            'wxpay_close' => (bool) $this->settings->get('wxpay_close', 'wxpay'),

            // 注册设置
            'setreg' => [
                'register_close' => (bool)$this->settings->get('register_close'),
                'register_validate' => (bool)$this->settings->get('register_validate'),
                'password_length' => (int)$this->settings->get('password_length'),
                'password_strength' => $this->settings->get('password_strength'),
            ],
        ];

        // 站点开关
        $attributes += $this->getSiteCloseSettings();

        // 站点模式
        $attributes += $this->getSiteModeSettings();

        // 当前用户是否存在
        if ($this->actor->exists) {
            $attributes['user'] = [
                'groups' => $this->actor->groups,
                'registerTime' => $this->formatDate($this->actor->created_at),
            ];

            // 当前用户是否是管理员
            if ($this->actor->isAdmin()) {
                // 站点设置
                $attributes['setsite'] = $this->getSiteSettings();

                // 第三方登陆设置
                $attributes['passport'] = $this->getPassportSettings();

                // 腾讯云设置
                $attributes['qcloud'] = $this->getQCloudSettings();

                // 提现设置
                $attributes['setcash'] = $this->getCashSettings();

                // 其他未分类设置
                $attributes += [
                    'siteAuthorScale' => $this->settings->get('site_author_scale'),
                    'siteMasterScale' => $this->settings->get('site_master_scale'),
                ];
            } else {
                //@todo 临时返回 非管理员 前台需要的字段
                $attributes['qcloud']['qcloud_close'] = (bool)$this->settings->get('qcloud_close', 'qcloud');
                $attributes['qcloud']['qcloud_cms_image'] = (bool)$this->settings->get('qcloud_cms_image', 'qcloud');
                $attributes['qcloud']['qcloud_cms_text'] = (bool)$this->settings->get('qcloud_cms_text', 'qcloud');
                $attributes['qcloud']['qcloud_sms'] = (bool)$this->settings->get('qcloud_sms', 'qcloud');
            }
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
        if ($logo) {
            return $this->url->to('/storage/' . $logo);
        }
        return '';
    }

    private function decrypt($value = '')
    {
        return $value ? $this->encrypter->decrypt($value) : '';
    }

    /**
     * 站点开关
     *
     * @return array
     */
    private function getSiteCloseSettings()
    {
        // 站点开关
        $settings['siteClose'] = (bool) $this->settings->get('site_close');

        // 站点关闭时或管理员登录返回关闭信息
        if ($settings['siteClose'] || ($this->actor->exists && $this->actor->isAdmin())) {
            $settings['siteCloseMsg'] = $this->settings->get('site_close_msg');
        }

        return $settings;
    }

    /**
     * 站点模式
     *
     * @return array
     */
    private function getSiteModeSettings()
    {
        // pay: 付费模式 public: 公开模式
        $settings['siteMode'] = $this->settings->get('site_mode');
        $settings['setsite']['site_mode'] = $this->settings->get('site_mode');

        // 付费模式时返回站点价格、到期时间
        if ($settings['siteMode'] == 'pay' || ($this->actor->exists && $this->actor->isAdmin())) {
            $settings['sitePrice'] = $this->settings->get('site_price');
            $settings['setsite']['site_price'] = $this->settings->get('site_price');
            $settings['siteExpire'] = $this->settings->get('site_expire');
            $settings['setsite']['site_Expire'] = $this->settings->get('site_expire');
        }

        return $settings;
    }

    /**
     * 站点设置v2版
     *
     * @return array
     */
    private function getSiteSettings()
    {
        return [
            'site_name' => $this->settings->get('site_name'),
            'site_introduction' => $this->settings->get('site_introduction'),
            'site_mode' => $this->settings->get('site_mode'), //pay public
            'site_price' => $this->settings->get('site_price'),
            'site_expire' => $this->settings->get('site_expire'),
            'site_author_scale' => $this->settings->get('site_author_scale'),
            'site_master_scale' => $this->settings->get('site_master_scale'),
            'site_icp' => $this->settings->get('site_icp'),
            'site_stat' => $this->settings->get('site_stat'),
            'site_close' => (bool)$this->settings->get('site_close'),
            'site_close_msg' => $this->settings->get('site_close_msg'),
            'site_author' => User::where('id', $this->settings->get('site_author'))->first(['id', 'username']),
            // 'site_logo' => $this->logo($this->settings->get('site_logo')),
            // 'site_install' => $this->settings->get('site_install'),
        ];
    }

    /**
     * 第三方登陆设置
     *
     * @return array
     */
    private function getPassportSettings()
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
     * 腾讯云设置
     *
     * @return array
     */
    private function getQCloudSettings()
    {
        return [
            'qcloud_close' => (bool)$this->settings->get('qcloud_close', 'qcloud'),
            'qcloud_app_id' => $this->settings->get('qcloud_app_id', 'qcloud'),
            'qcloud_secret_id' => $this->settings->get('qcloud_secret_id', 'qcloud'),
            'qcloud_secret_key' => $this->settings->get('qcloud_secret_key', 'qcloud'),
            'qcloud_token' => $this->settings->get('qcloud_token', 'qcloud'),
            'qcloud_cms_image' => (bool)$this->settings->get('qcloud_cms_image', 'qcloud'),
            'qcloud_cms_text' => (bool)$this->settings->get('qcloud_cms_text', 'qcloud'),
            'qcloud_sms_app_id' => $this->settings->get('qcloud_sms_app_id', 'qcloud'),
            'qcloud_sms_app_key' => $this->settings->get('qcloud_sms_app_key', 'qcloud'),
            'qcloud_sms_template_id' => $this->settings->get('qcloud_sms_template_id', 'qcloud'),
            'qcloud_sms_sign' => $this->settings->get('qcloud_sms_sign', 'qcloud'),
        ];
    }

    /**
     * 提现设置
     *
     * @return array
     */
    private function getCashSettings()
    {
        return [
            'cash_interval_time' => $this->settings->get('cash_interval_time', 'cash'),
            'cash_rate' => $this->settings->get('cash_rate', 'cash'),
            'cash_min_sum' => $this->settings->get('cash_min_sum', 'cash') ?: '',
            'cash_max_sum' => $this->settings->get('cash_max_sum', 'cash'),
            'cash_sum_limit' => $this->settings->get('cash_sum_limit', 'cash'),
        ];
    }
}
