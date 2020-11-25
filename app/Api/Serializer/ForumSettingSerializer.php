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
use App\Models\Thread;
use App\Models\User;
use App\Settings\ForumSettingField;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Common\PubEnum;
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
        $actor = $this->getActor();

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
                'site_keywords' => $this->settings->get('site_keywords'),
                'site_introduction' => $this->settings->get('site_introduction'),
                'site_mode' => $this->settings->get('site_mode'), // pay public
//                'site_close' => (bool)$this->settings->get('site_close'),
                'site_manage' => json_decode($this->settings->get('site_manage'), true),
                'site_close_msg'=>$this->settings->get('site_close_msg'),
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
                'site_onlooker_price' => $this->settings->get('site_onlooker_price') ?: 0, // 默认围观值前端根据权限判断
                'site_master_scale' => $this->settings->get('site_master_scale'), // 站长比例
                'site_pay_group_close' => $this->settings->get('site_pay_group_close'), // 用户组购买开关
                'site_minimum_amount' => $this->settings->get('site_minimum_amount'),
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
                'wxpay_mchpay_close' => (bool)$this->settings->get('wxpay_mchpay_close', 'wxpay'),
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
                'qcloud_cos_doc_preview' => (bool)$this->settings->get('qcloud_cos_doc_preview', 'qcloud'),
            ],

            // 提现设置
            'set_cash' => [
                'cash_rate' => $this->settings->get('cash_rate', 'cash'), // 提现费率
                'cash_min_sum' => $this->settings->get('cash_min_sum', 'cash') ?: '',
            ],

            // 其它信息(非setting中的信息)
            'other' => [
                // 基础信息
                'count_threads' => (int) $this->settings->get('thread_count'),          // 站点主题数
                'count_posts' => (int) $this->settings->get('post_count'),              // 站点回复数
                'count_users' => (int) $this->settings->get('user_count'),              // 站点用户数

                // 管理权限
                'can_edit_user_group' => $actor->can('user.edit.group'),                // 修改用户用户组
                'can_edit_user_status' => $actor->can('user.edit.status'),              // 修改用户状态

                // 至少在一个分类下有查看权限 或 有全局查看权限
                'can_view_threads' => Category::getIdsWhereCan($actor, 'viewThreads')
                                            || $actor->can('viewThreads'),              // 查看主题列表

                // 发布权限
                'can_create_dialog' => $actor->can('dialog.create'),                                        // 发短消息
                'can_create_invite' => $actor->can('createInvite'),                                         // 发邀请
                'can_invite_user_scale' => $actor->can('other.canInviteUserScale'),                         // 发分成邀请
                'can_create_thread_paid' => $actor->can('createThreadPaid'),                                // 发付费内容
                'can_create_thread' => $actor->can('createThread.' . Thread::TYPE_OF_TEXT),                 // 发布文字
                'can_create_thread_long' => $actor->can('createThread.' . Thread::TYPE_OF_LONG),            // 发布长文
                'can_create_thread_video' => $actor->can('createThread.' . Thread::TYPE_OF_VIDEO),          // 发布视频
                'can_create_thread_image' => $actor->can('createThread.' . Thread::TYPE_OF_IMAGE),          // 发布图片
                'can_create_thread_audio' => $actor->can('createThread.' . Thread::TYPE_OF_AUDIO),          // 发布语音
                'can_create_thread_question' => $actor->can('createThread.' . Thread::TYPE_OF_QUESTION),    // 发布问答
                'can_create_thread_goods' => $actor->can('createThread.' . Thread::TYPE_OF_GOODS),          // 发布商品

                // 至少在一个分类下有发布权限
                'can_create_thread_in_category' => (bool) Category::getIdsWhereCan($actor, 'createThread'),

                // 上传权限
                'can_upload_attachments' => $actor->can('attachment.create.0'),         // 上传附件
                'can_upload_images' => $actor->can('attachment.create.1'),              // 上传图片

                // 其他
                'initialized_pay_password' => (bool) $actor->pay_password,              // 是否初始化支付密码
                'can_be_asked' => $actor->can('canBeAsked'),                            // 是否允许被提问
                'can_be_onlooker' => $this->settings->get('site_onlooker_price') > 0 && $actor->can('canBeOnlooker'),           // 是否允许被围观
                'create_thread_with_captcha' => ! $actor->isAdmin() && $actor->can('createThreadWithCaptcha'),                  // 发布内容需要验证码
                'publish_need_real_name' => ! $actor->isAdmin() && $actor->can('publishNeedRealName') && ! $actor->realname,    // 发布内容需要实名认证
                'publish_need_bind_phone' => ! $actor->isAdmin() && $actor->can('publishNeedBindPhone') && ! $actor->mobile,    // 发布内容需要绑定手机
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
//        if ($attributes['set_site']['site_close'] == 1) {
//            $attributes['set_site'] += $this->forumField->getSiteClose();
//        }

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
            $attributes['other']['can_create_thread_audio'] = false;
        }

        // 微信小程序请求时判断视频开关
        if (! $this->settings->get('miniprogram_video', 'wx_miniprogram') &&
            strpos(Arr::get($this->request->getServerParams(), 'HTTP_X_APP_PLATFORM'), 'wx_miniprogram') !== false) {
            $attributes['other']['can_create_thread_video'] = false;
        }
        //判断三种注册方式是否置灰禁用
        $attributes['sign_enable']=$this->getSignInEnable($attributes);

        // 判断用户是否存在
        if ($actor->exists) {

            // 当前用户信息
            $attributes['user'] = [
                'groups' => $actor->groups,
                'register_time' => $this->formatDate($actor->created_at),
            ];

            // 当前用户是否是管理员 - 补充返回数据
            if ($actor->isAdmin()) {
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

    /**
     *判断管理后台三个端的选项按钮是否禁用
     */
    private function getSignInEnable($attributes)
    {
        $siteManage = array_column($attributes['set_site']['site_manage'], null, 'key');
        $user_name = true;
        $mobile_phone = false;
        $wechat_direct = false;
        //配置了短信服务则允许使用手机号注册
        $attributes['qcloud']['qcloud_sms'] && $mobile_phone = true;
        //允许使用微信无感注册登陆
        //pc :1,h5:2,微信：3
        $p1 = [];
        $p2 = [];
        if ($siteManage[PubEnum::PC]['value']) {
            $p1[]=PubEnum::PC;
            if ($attributes['passport']['offiaccount_close'] && $attributes['passport']['oplatform_close']) {
                $p2[]=PubEnum::PC;
            }
        }
        if ($siteManage[PubEnum::H5]['value']) {
            $p1[]=PubEnum::H5;
            if ($attributes['passport']['offiaccount_close']) {
                $p2[]=PubEnum::H5;
            }
        }
        if ($siteManage[PubEnum::MinProgram]['value']) {
            $p1[]=PubEnum::MinProgram;
            if ($attributes['passport']['miniprogram_close']) {
                $p2[]=PubEnum::MinProgram;
            }
        }
        $p1 == $p2 && $wechat_direct = true;
        return [
            'user_name' => $user_name,
            'mobile_phone' => $mobile_phone,
            'wechat_direct' => $wechat_direct
        ];
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
