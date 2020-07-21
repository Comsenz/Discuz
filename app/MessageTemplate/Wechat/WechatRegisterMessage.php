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

namespace App\MessageTemplate\Wechat;

use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * 新用户注册并加入后 - 微信
 *
 * Class RegisterMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatRegisterMessage extends DatabaseMessage
{
    protected $settings;

    protected $url;

    protected $tplId = 13;

    public function __construct(SettingsRepository $settings, UrlGenerator $url)
    {
        $this->settings = $settings;
        $this->url = $url;
    }

    protected function titleReplaceVars()
    {
        // TODO: Implement titleReplaceVars() method.
        return [];
    }

    protected function contentReplaceVars($data)
    {
        return [
            $this->settings->get('site_name'),
            $this->notifiable->username,
            Carbon::now()->toDateTimeString(),
//            $this->notifiable->groups->pluck('name')->join('、'), // 用户组
            $this->url->to(''),
        ];
    }
}
