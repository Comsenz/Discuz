<?php


namespace App\MessageTemplate;


use Discuz\Foundation\Application;
use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Str;

class PostDeleteMessage extends DatabaseMessage
{
    protected $translator;

    public function __construct(Application $app)
    {
        $this->translator = $app->make('translator');
    }

    protected function getTitle() {
        return $this->translator->get('core.post_delete');
    }

    protected function getContent($data)
    {

        return $this->translator->get('core.post_mod_ok_detail', [
            'user' => $this->notifiable->username,
            'message' => Str::words($data['message'], 10)
        ]);
    }
}
