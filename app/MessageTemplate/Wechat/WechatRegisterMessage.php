<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate\Wechat;

use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * 新用户注册并加入后 - 微信
 *
 * Class RegisterMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatRegisterMessage extends DatabaseMessage
{
    protected $settings;

    protected $url;

    protected $tplId = 13;

    public function __construct(SettingsRepository $settings, UrlGenerator $url)
    {
        $this->settings = $settings;
        $this->url = $url;
    }

    protected function titleReplaceVars()
    {
        // TODO: Implement titleReplaceVars() method.
        return [];
    }

    protected function contentReplaceVars($data)
    {
        return [
            $this->settings->get('site_name'),
            $this->notifiable->username,
            Carbon::now()->toDateTimeString(),
//            $this->notifiable->groups->pluck('name')->join('、'), // 用户组
            $this->url->to(''),
        ];
    }
}
