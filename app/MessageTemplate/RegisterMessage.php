<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Notifications\Messages\DatabaseMessage;

class RegisterMessage extends DatabaseMessage
{
    protected $settings;

    protected $tplId = 1;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    protected function titleReplaceVars()
    {
        return [
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
