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
 * 根据用户状态变更 发送不同的通知 - 微信
 *
 * Class StatusMessage
 * @property UrlGenerator url
 * @package App\MessageTemplate
 */
class WechatStatusMessage extends DatabaseMessage
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
        $refuse = '无';
        if (Arr::has($data, 'refuse')) {
            if (!empty($data['refuse'])) {
                $refuse = $data['refuse'];
            }
        }

        return [
            $this->notifiable->username,
            Carbon::now()->toDateTimeString(),
            $this->url->to(''),
            $refuse,
        ];
    }
}
