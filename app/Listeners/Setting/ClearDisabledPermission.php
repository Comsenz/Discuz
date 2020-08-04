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
        // 关闭微信支付时
        if (! $this->settings->get('wxpay_close', 'wxpay')) {
            // 站点模式设为公开模式
            $this->settings->set('site_mode', 'public');

            // 清除 「发布付费贴和被支付」权限
            Permission::query()->where('permission', 'createThreadPaid')->delete();
        }

        // 关闭腾讯云短信时，清除「发布内容需先绑定手机」权限
        if (! $this->settings->get('qcloud_sms', 'qcloud')) {
            Permission::query()->where('permission', 'publishNeedBindPhone')->delete();
        }

        // 关闭腾讯云实名认证时，清除「发布内容需先实名认证」权限
        if (! $this->settings->get('qcloud_faceid', 'qcloud')) {
            Permission::query()->where('permission', 'publishNeedRealName')->delete();
        }
    }
}
