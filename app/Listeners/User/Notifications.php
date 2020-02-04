<?php


namespace App\Listeners\User;


use App\Events\Users\Registered;
use App\MessageTemplate\RegisterMessage;
use App\Notifications\System;

class Notifications
{

    public function handle(Registered $event)
    {
        $event->user->notify(new System(RegisterMessage::class));
    }
}
