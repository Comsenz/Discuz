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
 * 内容支付通知 - 微信
 * (包含: 注册分成/打赏分成帖子/付费贴分成)
 *
 * @package App\MessageTemplate\Wechat
 */
class WechatRewardedScaleMessage extends DatabaseMessage
{
    protected $tplId = 38;

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
        $bossAmount = Arr::get($data, 'raw.boss_amount', 0); // 上级分成金额

        // 获取支付类型
        $orderName = Order::enumType(Arr::get($data, 'raw.type', 0), function ($args) {
            return $args['value'];
        });

        $actorName = Arr::get($data, 'raw.actor_username', '');  // 发送人姓名

        // 主题ID为空时跳转到首页
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/topic/index?id=' . $threadId);
        }

        return [
            $actorName,
            $bossAmount,
            $this->strWords($message),
            $orderName, // 1：注册，2：打赏，3：付费主题，4：付费用户组
            Carbon::now()->toDateTimeString(),
            $threadUrl,
        ];
    }
}
