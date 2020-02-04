<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Discuz\Notifications\Messages\DatabaseMessage;

class StatusMessage extends DatabaseMessage
{
    protected $translator;

    protected $settings;

    public function __construct(Application $app, SettingsRepository $settings)
    {
        $this->translator = $app->make('translator');
        $this->settings = $settings;
    }

    protected function getTitle()
    {
        $actionType = User::enumStatus($this->notifiable->status);
        // status_mod_change
        $lang = $this->isMod() ? "core.status_{$actionType}_change" : 'core.status_mod_change';
        return $this->translator->get($lang);
    }

    protected function getContent($data)
    {
        $actionType = User::enumStatus($this->notifiable->status);
        $replace = [
            'user' => $this->notifiable->username,
        ];
        if ($this->notifiable->status) {
            $replace['refuse'] = $data['refuse'];
        }

        // status_refuse_change_detail
        $lang = "core.status_{$actionType}_change_detail";
        if ($this->isMod()) {
            $this->notifiable->status == 0 && $lang = 'core.status_mod_normal_change_detail';
        }

        return $this->translator->get($lang, $replace);
    }

    protected function isMod()
    {
        return false;//(bool)$this->settings->get('register_validate', 0);
    }
}
