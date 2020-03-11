<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Thread;
use App\Models\User;
use App\Settings\ForumSettingField;
use Carbon\Carbon;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;

class ForumSettingSerializer extends AbstractSerializer
{
    protected $type = 'forums';

    protected $settings;

    protected $forumField;

    public function __construct(SettingsRepository $settings, ForumSettingField $forumField)
    {
        $this->settings = $settings;
        $this->forumField = $forumField;
    }

    /**
     * @param array|object $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        // 获取logo完整地址
        $logo = $this->forumField->siteUrlSplicing($this->settings->get('logo'));

        $attributes = [
            // 站点设置
            'set_site' => [
                'site_name' => $this->settings->get('site_name'),
                'site_introduction' => $this->settings->get('site_introduction'),
                'site_mode' => $this->settings->get('site_mode'), // pay public
                'site_close' => (bool)$this->settings->get('site_close'),
                'site_logo' => $logo ? $logo . '?' . Carbon::now()->timestamp : '', // 拼接日期
                'site_url' => $this->settings->get('site_url'),
                'site_icp' => $this->settings->get('site_icp') ?: '',
                'site_stat' => $this->settings->get('site_stat') ?: '',
                'site_author' => User::where('id', $this->settings->get('site_author'))->first(['id', 'username']),
                'site_install' => $this->settings->get('site_install'), // 安装时间
            ],

            // 注册设置
            'set_reg' => [
                'register_close' => (bool)$this->settings->get('register_close'),
                'register_validate' => (bool)$this->settings->get('register_validate'),
                'password_length' => (int)$this->settings->get('password_length'),
                'password_strength' => empty($this->settings->get('password_strength')) ? [] : explode(',', $this->settings->get('password_strength')),
            ],

            // 第三方登录设置
            'passport' => [
                'offiaccount_close' => $this->settings->get('offiaccount_close', 'wx_offiaccount'), // 微信H5 开关
                'miniprogram_close' => $this->settings->get('miniprogram_close', 'wx_miniprogram'), // 微信小程序 开关
                'oplatform_close' => $this->settings->get('oplatform_close', 'wx_oplatform'),       // 微信PC 开关
            ],

            // 支付设置
            'paycenter' => [
                'wxpay_close' => $this->settings->get('wxpay_close', 'wxpay'),
            ],

            // 附件设置
            'set_attach' => [
                'support_img_ext' => $this->settings->get('support_img_ext', 'default'),
                'support_file_ext' => $this->settings->get('support_file_ext', 'default'),
                'support_max_size' => $this->settings->get('support_max_size', 'default'),
            ],

            // 腾讯云设置
            'qcloud' => [
                'qcloud_app_id' => $this->settings->get('qcloud_app_id', 'qcloud'),
                'qcloud_close' => (bool)$this->settings->get('qcloud_close', 'qcloud'),
                'qcloud_cos' => (bool)$this->settings->get('qcloud_cos', 'qcloud'),
                'qcloud_captcha' => (bool)$this->settings->get('qcloud_captcha', 'qcloud'),
                'qcloud_captcha_app_id' => $this->settings->get('qcloud_captcha_app_id', 'qcloud'),
                'qcloud_faceid' => (bool)$this->settings->get('qcloud_faceid', 'qcloud'),
                'qcloud_sms' => (bool)$this->settings->get('qcloud_sms', 'qcloud'),
                'qcloud_vod' => (bool)$this->settings->get('qcloud_vod', 'qcloud'),
            ],

            // 提现设置
            'set_cash' => [
                'cash_rate' => $this->settings->get('cash_rate', 'cash'), // 提现费率
            ],

            // 其它信息(非setting中的信息)
            'other' => [
                // 基础信息
                'count_threads' => Thread::where('is_approved', Thread::APPROVED)->whereNull('deleted_at')->count(), // 统计所有主题数
                'count_users' => User::where('status', 0)->count(), // 统计所有的用户
                // 权限 permission
                'can_upload_attachments' => $this->actor->can('attachment.create.0'),
                'can_upload_images' => $this->actor->can('attachment.create.1'),
                'can_create_thread' => $this->actor->can('createThread'),
                'can_view_threads' => $this->actor->can('viewThreads'),
                'can_batch_edit_threads' => $this->actor->can('thread.batchEdit'),
                'can_view_user_list' => $this->actor->can('viewUserList'),
                'can_edit_user_group' => $this->actor->can('user.edit.group'),
                'can_create_invite' => $this->actor->can('createInvite'),
                'create_thread_with_captcha' => !$this->actor->isAdmin() && $this->actor->can('createThreadWithCaptcha'),
                'initialized_pay_password' => (bool)$this->actor->pay_password,  // 是否初始化支付密码
            ],
        ];

        // 站点开关 - 满足条件返回
        if ($attributes['set_site']['site_close'] == 1) {
            $attributes['set_site'] += $this->forumField->getSiteClose();
        }

        // 付费模式 - 满足条件返回
        if ($attributes['set_site']['site_mode'] == 'pay') {
            $attributes['set_site'] += $this->forumField->getSitePayment();
        }

        // 开启视频服务 - 满足条件返回
        if ($attributes['qcloud']['qcloud_vod'] == '1') {
            $attributes['qcloud'] += $this->forumField->getQCloudVod();
        }

        // 判断用户是否存在
        if ($this->actor->exists) {

            // 当前用户信息
            $attributes['user'] = [
                'groups' => $this->actor->groups,
                'register_time' => $this->formatDate($this->actor->created_at),
            ];

            // 当前用户是否是管理员 - 补充返回数据
            if ($this->actor->isAdmin()) {
                // 站点设置
                $attributes['set_site'] += $this->forumField->getSiteSettings();

                // 第三方登录设置
                $attributes['passport'] += $this->forumField->getPassportSettings();

                // 支付设置
                $attributes['paycenter'] += $this->forumField->getPaycenterSettings();

                // 腾讯云设置
                $attributes['qcloud'] += $this->forumField->getQCloudSettings();

                // 提现设置
                $attributes['set_cash'] += $this->forumField->getCashSettings();
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
}
