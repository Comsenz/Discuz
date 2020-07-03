<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Logind;
use Discuz\Auth\Exception\PermissionDeniedException;

class BanLogin
{
    public function handle($event)
    {
        $user = $event->user;
        switch ($user->status) {
            case 1:
                throw new PermissionDeniedException('ban_user');
                break;
            case 3:
                throw new PermissionDeniedException('validate_reject');
                break;
            case 4:
                throw new PermissionDeniedException('validate_ignore');
                break;
        }
    }
}
