<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Registered;
use App\Models\Group;
use Illuminate\Support\Arr;

class AddDefaultGroup
{
    public function handle(Registered $event)
    {
        if (!Arr::get($event->data, 'code')) {
            $defaultGroup = Group::where('default', true)->first();
            $event->user->groups()->sync($defaultGroup->id);
        }
    }
}
