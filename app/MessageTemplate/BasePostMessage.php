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
         * 判断是否有主题标题
         * 使用首贴内容代替 并过滤 内容的标签
         */
        $thread = Arr::get($data, 'message', false);
        $message = '';
        if ($thread) {
            $message = empty($thread->title) ? $message = $thread->firstPost->content : $thread->title;
        }

        $threadUrl = $this->url->to('/details/'.$thread->id);

        return [
            $this->notifiable->username,
            '<a href="'.$threadUrl.'">'.Str::words($message, 10).'</a>',
            Arr::get($data, 'refuse', '')
        ];
    }
}
