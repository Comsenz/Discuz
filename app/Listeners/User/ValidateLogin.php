<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Logind;
use Discuz\Auth\Exception\PermissionDeniedException;

class ValidateLogin
{
    public function handle($event)
    {
        $user = $event->user;
        if ($user->status == 2) {
            throw new PermissionDeniedException('register_validate');
        }
    }
}
