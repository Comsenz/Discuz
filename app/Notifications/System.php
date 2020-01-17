<?php


namespace App\Notifications;


use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class System extends Notification
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return $this->data;
    }
}
