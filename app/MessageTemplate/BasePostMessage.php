<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;

/**
 * 系统Post通知 - 基类
 *
 * Class BasePostMessage
 * @package App\MessageTemplate
 */
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

        return [
            $this->notifiable->username,
            $this->strWords($message),
            Arr::get($data, 'refuse', '无')
        ];
    }
}
