<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Saving;
use App\Models\Group;
use App\Models\User;

class AddDefaultGroup
{
    public function handle(Saving $event)
    {
        $user = $event->user;
        $defaultGroup = Group::where('default', Group::DEFAULT)->first();

        $user->saved(function (User $user) use ($defaultGroup) {
            $user->groups()->sync($defaultGroup->id);
        });
    }
}
