<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate\Wechat;

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * 用户角色调整通知 - 微信
 *
 * Class GroupMessage
 * @package App\MessageTemplate
 */
class WechatGroupMessage extends DatabaseMessage
{
    protected $url;

    protected $tplId = 24;

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
        $oldGroup = $data['old'];
        $newGroup = $data['new'];

        // 跳转到首页
        $redirectUrl = $this->url->to('');

        return [
            $this->notifiable->username,
            $oldGroup->pluck('name')->join('、'),
            $newGroup->pluck('name')->join('、'),
            $redirectUrl,
        ];
    }
}
