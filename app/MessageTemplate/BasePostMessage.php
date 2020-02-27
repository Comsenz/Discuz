<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Foundation\Application;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BasePostMessage extends DatabaseMessage
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
        /**
         * 格式：
         * [
         *     'message' => '标题名'
         *     'refuse' => '拒绝原因'
         *     'raw' => [
         *          'thread_id' => 1
         *          'is_first'  => false
         *     ]
         * ]
         **/
        $message = Arr::get($data, 'message', '');
        $threadId = Arr::get($data, 'raw.thread_id', 0);
        $threadUrl = $this->url->to('/details/' . $threadId);

        return [
            $this->notifiable->username,
            '<a href="' . $threadUrl . '">' . Str::words($message, 10) . '</a>',
            Arr::get($data, 'refuse', '无')
        ];
    }
}
