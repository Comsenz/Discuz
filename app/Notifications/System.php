<?php


namespace App\Notifications;


use Illuminate\Notifications\Notification;

class System extends Notification
{

    protected $data;
    protected $type;

    public function __construct($type, $data = [])
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $message = app()->make($this->type);

        return $message->notifiable($notifiable)->template($this->data);
    }



}
