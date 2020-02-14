<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Arr;

class StatusMessage extends DatabaseMessage
{
    protected $settings;

    protected $tplIds = [
        'ban' => 10,
        'normal' => 11,
        'mod_normal' => 2,
        'mod' => 3
    ];

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    public function template($data)
    {
        $actionType = User::enumStatus($this->notifiable->status);
        if($this->isMod()) {
            $this->notifiable->status == 0 && $actionType .= '_normal';
        }
        $this->tplId = Arr::get($this->tplIds, $actionType);
        return parent::template($data);
    }

    protected function titleReplaceVars()
    {
        return [];
    }

    protected function contentReplaceVars($data)
    {
        return [
            $this->notifiable->username,
            Arr::get($data, 'refuse', '')
        ];
    }

    protected function isMod()
    {
        return (bool)$this->settings->get('register_validate', 0);
    }
}
