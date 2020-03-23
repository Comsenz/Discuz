<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Registered;
use App\Models\Group;

class AddDefaultGroup
{
    public function handle(Registered $event)
    {
        $user = $event->user;
        $defaultGroup = Group::where('default', true)->first();

        if (!$event->user->groups) {
            $user->groups()->sync($defaultGroup->id);
        }
    }
}
