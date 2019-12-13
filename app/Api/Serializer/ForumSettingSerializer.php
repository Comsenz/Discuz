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

            // 腾讯云设置
            'qcloud' => (bool)$this->settings->get('qcloud'),
            'qcloud_cms_image' => (bool)$this->settings->get('qcloud_cms_image'),
            'qcloud_cms_text' => (bool)$this->settings->get('qcloud_cms_text'),
            'qcloud_sms' => (bool)$this->settings->get('qcloud_sms'),
            'qcloud_secret_id' => $this->decrypt($this->settings->get('qcloud_secret_id')),
            'qcloud_secret_key' => $this->decrypt($this->settings->get('qcloud_secret_key')),
            'qcloud_token' => $this->decrypt($this->settings->get('qcloud_token')),

            // 登陆配置
            'wechat_h5'     => (bool) $this->settings->get('wechat_h5', 'wechat_h5'),
            'wechat_min'    => (bool) $this->settings->get('wechat_min', 'wechat_min'),
            'wechat_pc'     => (bool) $this->settings->get('wechat_pc', 'wechat_pc'),
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
