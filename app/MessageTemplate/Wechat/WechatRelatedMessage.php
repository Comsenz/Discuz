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
 * 内容@通知 - 微信
 *
 * Class WechatRelatedMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatRelatedMessage extends DatabaseMessage
{
    protected $tplId = 32;

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
        $replyPostId = Arr::get($data, 'raw.reply_post_id', 0); // 楼中楼时不为0

        /**
         * TODO 判断是否是楼中楼
         * 主题ID为空时跳转到首页
         */
        if (empty($threadId)) {
            $threadUrl = $this->url->to('');
        } else {
            $threadUrl = $this->url->to('/details/' . $threadId);
        }

        return [
            $this->notifiable->username,        // 用户名
            $this->strWords($message),          // @内容
            Carbon::now()->toDateTimeString(),  // 通知时间
            $threadUrl,                         // 跳转地址
        ];
    }
}
