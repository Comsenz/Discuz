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

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * 用户角色调整通知 - 微信
 *
 * Class GroupMessage
 * @package App\MessageTemplate
 */
class WechatGroupMessage extends DatabaseMessage
{
    protected $url;

    protected $tplId = 24;

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    protected function contentReplaceVars($data)
    {
        $oldGroup = $data['old'];
        $newGroup = $data['new'];

        // 跳转到首页
        $redirectUrl = $this->url->to('');

        return [
            $this->notifiable->username,
            $oldGroup->pluck('name')->join('、'),
            $newGroup->pluck('name')->join('、'),
            $redirectUrl,
        ];
    }
}
