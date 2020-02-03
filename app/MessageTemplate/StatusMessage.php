<?php


namespace App\MessageTemplate;


use App\Models\User;
use Discuz\Foundation\Application;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Translation\Translator;

class StatusMessage extends DatabaseMessage
{
    protected $translator;

    public function __construct(Application $app)
    {
        $this->translator = $app->make('translator');
    }

    protected function getTitle() {
        $actionType = User::enumStatus($this->notifiable->status);
        return $this->translator->get("core.status_{$actionType}_change");
    }

    protected function getContent($data)
    {
        $actionType = User::enumStatus($this->notifiable->status);
        $replace = [
            'user' => $this->notifiable->username,
        ];
        if($this->notifiable->status) {
            $replace['refuse'] = $data['refuse'];
        }
        return $this->translator->get("core.status_{$actionType}_change_detail", $replace);
    }
}
