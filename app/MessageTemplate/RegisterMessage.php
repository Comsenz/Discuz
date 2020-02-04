<?php


namespace App\MessageTemplate;


use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Str;

class RegisterMessage extends DatabaseMessage
{
    protected $translator;
    protected $settings;

    public function __construct(Application $app, SettingsRepository $settings)
    {
        $this->translator = $app->make('translator');
        $this->settings = $settings;
    }

    protected function getTitle() {
        return $this->translator->get('core.register', [
            'sitename' => $this->settings->get('site_name')
        ]);
    }

    protected function getContent($data)
    {
        return $this->translator->get('core.register_detail', [
            'user' => $this->notifiable->username,
            'sitename' => $this->settings->get('site_name'),
            'group' => $this->notifiable->groups->pluck('name')->join('、'),
        ]);
    }
}
