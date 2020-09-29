<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Serializer;

use App\Models\Category;
use App\Models\User;
use App\Settings\ForumSettingField;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\UrlGenerator;
use Illuminate\Support\Arr;

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
        $favicon = $this->forumField->siteUrlSplicing($this->settings->get('favicon'));
        $logo = $this->forumField->siteUrlSplicing($this->settings->get('logo'));
        $headerLogo = $this->forumField->siteUrlSplicing($this->settings->get('header_logo'));
        $backgroundImage = $this->forumField->siteUrlSplicing($this->settings->get('background_image'));

        $port = $this->request->getUri()->getPort();
        $siteUrl = $this->request->getUri()->getScheme() . '://' . $this->request->getUri()->getHost().(in_array($port, [80, 443, null]) ? '' : ':'.$port);

        $attributes = [
            // 站点设置
            'set_site' => [
                'site_name' => $this->settings->get('site_name'),
                'site_title' => $this->settings->get('site_title'),
                'site_introduction' => $this->settings->get('site_introduction'),
                'site_mode' => $this->settings->get('site_mode'), // pay public
                'site_close' => (bool)$this->settings->get('site_close'),
                'site_favicon' => $favicon ?: app(UrlGenerator::class)->to('/favicon.ico'),
                'site_logo' => $logo ?: '',
                'site_header_logo' => $headerLogo ?: '',
                'site_background_image' => $backgroundImage ?: '',
                'site_url' => $siteUrl,
                'site_stat' => $this->settings->get('site_stat') ?: '',
                'site_author' => User::query()->where('id', $this->settings->get('site_author'))->first(['id', 'username', 'avatar']),
                'site_install' => $this->settings->get('site_install'), // 安装时间
                'site_record' => $this->settings->get('site_record'),
                'site_cover' => $this->settings->get('site_cover') ?: '',
                'site_record_code' => $this->settings->get('site_record_code') ?: '',
            ],

            // 注册设置
            'set_reg' => [
                'register_close' => (bool)$this->settings->get('register_close'),
                'register_validate' => (bool)$this->settings->get('register_validate'),
                'register_captcha' => (bool)$this->settings->get('register_captcha'),
                'password_length' => (int)$this->settings->get('password_length'),
                'password_strength' => empty($this->settings->get('password_strength')) ? [] : explode(',', $this->settings->get('password_strength')),
                'register_type' => (int)$this->settings->get('register_type', 'default', 0),
            ],

            // 第三方登录设置
            'passport' => [
                'offiaccount_close' => (bool)$this->settings->get('offiaccount_close', 'wx_offiaccount'), // 微信H5 开关
                'miniprogram_close' => (bool)$this->settings->get('miniprogram_close', 'wx_miniprogram'), // 微信小程序 开关
                'oplatform_close' => (bool)$this->settings->get('oplatform_close', 'wx_oplatform'),       // 微信PC 开关
            ],

            // 支付设置
            'paycenter' => [
                'wxpay_close' => (bool)$this->settings->get('wxpay_close', 'wxpay'),
                'wxpay_ios' => (bool)$this->settings->get('wxpay_ios', 'wxpay'),
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
                'count_threads' => (int)$this->settings->get('thread_count'), // 统计所有主题数
                'count_posts' => (int)$this->settings->get('post_count'), // 统计所有回复数
                'count_users' => (int)$this->settings->get('user_count'), // 统计所有的用户
                // 权限 permission
                'can_upload_attachments' => $this->actor->can('attachment.create.0'),
                'can_upload_images' => $this->actor->can('attachment.create.1'),
                'can_create_thread' => $this->actor->can('createThread'),
                'can_create_thread_long' => $this->actor->can('createThreadLong'),
                'can_create_thread_video' => $this->actor->can('createThreadVideo'),
                'can_create_thread_image' => $this->actor->can('createThreadImage'),
                'can_create_thread_audio' => $this->actor->can('createThreadAudio'),
                'can_create_thread_in_category' => (bool)Category::getIdsWhereCan($this->actor, 'createThread'),
                'can_create_audio' => $this->actor->can('createAudio'),
                'can_create_dialog' => $this->actor->can('dialog.create'),
                'can_view_threads' => $this->actor->can('viewThreads'),
                'can_batch_edit_threads' => $this->actor->can('thread.batchEdit'),
                'can_view_user_list' => $this->actor->can('viewUserList'),
                'can_edit_user_group' => $this->actor->can('user.edit.group'),
                'can_edit_user_status' => $this->actor->can('user.edit.status'),
                'can_create_invite' => $this->actor->can('createInvite'),
                'can_create_thread_paid' => $this->actor->can('createThreadPaid'),
                'create_thread_with_captcha' => ! $this->actor->isAdmin() && $this->actor->can('createThreadWithCaptcha'),
                'publish_need_real_name' => ! $this->actor->isAdmin() && $this->actor->can('publishNeedRealName') && ! $this->actor->realname,
                'publish_need_bind_phone' => ! $this->actor->isAdmin() && $this->actor->can('publishNeedBindPhone') && ! $this->actor->mobile,
                'initialized_pay_password' => (bool)$this->actor->pay_password,  // 是否初始化支付密码
                'can_invite_user_scale' => $this->actor->can('other.canInviteUserScale'),
            ],

            'lbs' => [
                'lbs' => (bool) $this->settings->get('lbs', 'lbs'),         // 位置服务开关
                'qq_lbs_key' => $this->settings->get('qq_lbs_key', 'lbs'),  // 腾讯位置服务 key
            ],

            'ucenter' => [
                'ucenter' => (bool) $this->settings->get('ucenter', 'ucenter'),
            ]
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
        if ($attributes['qcloud']['qcloud_close'] && $attributes['qcloud']['qcloud_vod']) {
            $attributes['qcloud'] += $this->forumField->getQCloudVod();
        } else {
            //未开启vod服务 不可发布视频主题
            $attributes['other']['can_create_thread_video'] = false;
        }

        // 微信小程序请求时判断视频开关
        if (! $this->settings->get('miniprogram_video', 'wx_miniprogram') &&
            strpos(Arr::get($this->request->getServerParams(), 'HTTP_X_APP_PLATFORM'), 'wx_miniprogram') !== false) {
            $attributes['other']['can_create_thread_video'] = false;
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

                // 水印设置
                $attributes['watermark'] = $this->forumField->getWatermarkSettings();

                // UCenter设置
                $attributes['ucenter'] += $this->forumField->getUCenterSettings();
            }
        }

        return $attributes + Arr::except($model, 'id');
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
