<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate\Wechat;

use Carbon\Carbon;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 内容点赞通知 - 微信
 *
 * Class WechatLikedMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatLikedMessage extends DatabaseMessage
{
    protected $tplId = 30;

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
        $actorName = Arr::get($data, 'raw.actor_username', '');  // 发送人姓名

        // 主题ID为空时跳转到首页
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/details/' . $threadId);
        }

        return [
            $actorName,
            $this->strWords($message),
            Carbon::now()->toDateTimeString(),
            $threadUrl,
        ];
    }
}
