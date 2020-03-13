<?php

namespace App\Listeners\User;

use App\Events\Users\Registered;
use App\MessageTemplate\RegisterMessage;
use App\Notifications\System;

/**
 * 通知行为 - 系统通知
 *
 * Class Notifications
 * @package App\Listeners\User
 */
class Notifications
{
    public function handle(Registered $event)
    {
        $event->user->notify(new System(RegisterMessage::class));
    }
}
