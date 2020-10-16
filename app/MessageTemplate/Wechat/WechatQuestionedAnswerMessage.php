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

use App\Models\Order;
use Carbon\Carbon;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 问答回答通知  - 微信
 *
 * Class WechatQuestionedAnswerMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatQuestionedAnswerMessage extends DatabaseMessage
{
    protected $tplId = 42;

    protected $url;

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
        $message = Arr::get($data, 'message', '');
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        $actorName = Arr::get($data, 'raw.actor_username', ''); // 回答人姓名

        // 主题ID为空时跳转到首页
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/topic/index?id=' . $threadId);
        }

        return [
            $actorName,
            $this->strWords($message),
            Carbon::now()->toDateTimeString(),
            $threadUrl,
        ];
    }
}
