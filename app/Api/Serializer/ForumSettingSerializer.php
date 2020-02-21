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
                'site_icp' => $this->settings->get('site_icp'),
                'site_stat' => $this->settings->get('site_stat'),
                'site_author' => User::where('id', $this->settings->get('site_author'))->first(['id', 'username']),
            ],

            // 注册设置
            'set_reg' => [
                'register_close' => (bool)$this->settings->get('register_close'),
                'register_validate' => (bool)$this->settings->get('register_validate'),
                'password_length' => (int)$this->settings->get('password_length'),
                'password_strength' => empty($this->settings->get('password_strength')) ? [] : explode(',', $this->settings->get('password_strength')),
            ],

            // 第三方登陆设置
            'passport' => [],
            // 支付设置
            'paycenter' => [],

            // 附件设置
            'set_attach' => [
                'support_img_ext' => $this->settings->get('support_img_ext', 'default'),
                'support_file_ext' => $this->settings->get('support_file_ext', 'default'),
                'support_max_size' => $this->settings->get('support_max_size', 'default'),
            ],

            // 腾讯云设置
            'qcloud' => [
                'qcloud_sms' => (bool)$this->settings->get('qcloud_sms', 'qcloud'),
            ],

            // 提现设置
            'set_cash' => [],

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
                'can_viewUser_list' => $this->actor->can('viewUserList'),
                'can_editUser_group' => $this->actor->can('user.edit.group'),
                'can_create_invite' => $this->actor->can('createInvite'),
            ],
        ];

        // 站点开关 - 满足条件返回
        if ($attributes['set_site']['site_close'] || ($this->actor->exists && $this->actor->isAdmin())) {
            $attributes['set_site'] += $this->forumField->getSiteClose();
        }

        // 付费模式 - 满足条件返回
        if ($attributes['set_site']['site_mode'] == 'pay' || ($this->actor->exists && $this->actor->isAdmin())) {
            $attributes['set_site'] += $this->forumField->getSitePayment();
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

                // 第三方登陆设置
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
