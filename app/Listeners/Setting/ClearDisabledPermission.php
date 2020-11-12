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

namespace App\Listeners\Setting;

use App\Events\Setting\Saved;
use App\Models\Permission;
use App\Models\Thread;
use Discuz\Contracts\Setting\SettingsRepository;

class ClearDisabledPermission
{
    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @param SettingsRepository $settings
     */
    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param Saved $event
     */
    public function handle(Saved $event)
    {
        // 修改设置时，需要清理的权限
        $permissions = [];

        // 关闭微信支付时
        if (! $this->settings->get('wxpay_close', 'wxpay')) {
            // 站点模式设为公开模式
            $this->settings->set('site_mode', 'public');

            $permissions[] = 'createThreadPaid';            // 发布付费内容
            $permissions[] = 'canBeReward';                 // 允许被打赏
        }

        // 关闭腾讯云短信时
        if (! $this->settings->get('qcloud_sms', 'qcloud')) {
            // 注册模式如果是「手机号优先」则设为「用户名优先」
            if ($this->settings->get('register_type') == 1) {
                $this->settings->set('register_type', 0);
            }

            $permissions[] = 'publishNeedBindPhone';        // 发布内容需先绑定手机
        }

        // 关闭腾讯云实名认证时
        if (! $this->settings->get('qcloud_faceid', 'qcloud')) {
            $permissions[] = 'publishNeedRealName';         // 发布内容需先实名认证
        }

        // 关闭腾讯云点播时
        if (! $this->settings->get('qcloud_vod', 'qcloud')) {
            $permissions[] = 'createThread.' . Thread::TYPE_OF_VIDEO;   // 发布视频帖
            $permissions[] = 'createThread.' . Thread::TYPE_OF_AUDIO;   // 发布语音帖
        }

        // 清理权限
        if ($permissions) {
            Permission::query()->whereIn('permission', $permissions)->delete();
        }
    }
}
