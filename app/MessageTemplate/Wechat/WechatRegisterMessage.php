<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate\Wechat;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Notifications\Messages\DatabaseMessage;

/**
 * 新用户注册并加入后 - 微信
 *
 * Class RegisterMessage
 * @package App\MessageTemplate\Wechat
 */
class WechatRegisterMessage extends DatabaseMessage
{
    protected $settings;

    protected $tplId = 13;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    protected function titleReplaceVars()
    {
        return [
            '',
            $this->settings->get('site_name')
        ];
    }

    protected function contentReplaceVars($data)
    {
        return [
            $this->notifiable->username,
            $this->settings->get('site_name'),
            $this->notifiable->groups->pluck('name')->join('、'),
        ];
    }
}
