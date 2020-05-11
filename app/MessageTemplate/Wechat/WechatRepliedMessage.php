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
 * 内容回复通知 - 微信
 *
 * Class WechatRepliedMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatRepliedMessage extends DatabaseMessage
{
    protected $tplId = 29;

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
        $subject = Arr::get($data, 'subject', '');
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        $replyPostId = Arr::get($data, 'raw.reply_post_id', 0); // 楼中楼时不为0
        $actorName = Arr::get($data, 'raw.actor_username', '');  // 发送人姓名

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
            $actorName,                         // 回复人的用户名
            $this->strWords($message),          // 回复内容
            $this->strWords($subject),          // 原内容
            Carbon::now()->toDateTimeString(),  // 通知时间
            $threadUrl,                         // 跳转地址
        ];
    }
}
