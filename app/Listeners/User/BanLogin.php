<?php


namespace App\Listeners\User;


use App\Events\Users\Logind;
use Discuz\Auth\Exception\PermissionDeniedException;

class BanLogin
{
    public function handle(Logind $event)
    {
        $user = $event->user;
        if($user->status) {
            throw new PermissionDeniedException('ban_user');
        }
    }
}
