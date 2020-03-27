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

        if ($event->user->groups->isEmpty()) {
            $defaultGroup = Group::where('default', true)->first();
            $user->groups()->sync($defaultGroup->id);
        }
    }
}
