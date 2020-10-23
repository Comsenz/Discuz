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

use App\Models\Question;
use Carbon\Carbon;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 过期通知 - 微信
 *
 * @package App\MessageTemplate\Wechat
 */
class WechatExpiredMessage extends DatabaseMessage
{
    protected $tplId = 44;

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
        $name = '';
        $detail = '';
        $content = Arr::get($data, 'content', ''); // 主题内容

        // 问答过期通知模型数据
        if (Arr::get($data, 'raw.model') instanceof Question) {
            /** @var Question $question */
            $question = Arr::get($data, 'raw.model');
            $name = '您的问题超时未收到回答';
            $detail = '返还金额' . $question->price;    // 解冻金额
        }

        // 通知时间
        $dateLine = Carbon::now()->toDateTimeString();

        // 主题ID为空时跳转到首页
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/topic/index?id=' . $threadId);
        }

        return [
            $name,          // {username}       谁
            $detail,        // {detail}         xx已过期
            $content,       // {content}        内容
            $dateLine,      // {dateline}       通知时间
            $threadUrl,     // {redirecturl}    跳转地址
        ];
    }
}
