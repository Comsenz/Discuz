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
use Illuminate\Support\Str;

/**
 * 微信Post通知 - 基类
 *
 * Class WechatMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatMessage extends DatabaseMessage
{
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
        $threadUrl = $this->url->to('/details/' . $threadId);

        return [
            Str::words($message, 10),
            Carbon::now(),
            $threadUrl,
            Arr::get($data, 'refuse', '无'),
        ];
    }
}
