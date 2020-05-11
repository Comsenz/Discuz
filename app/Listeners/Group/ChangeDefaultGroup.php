<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Group;

use App\Events\Group\Saving;
use App\Models\Group;
use Illuminate\Support\Arr;

class ChangeDefaultGroup
{
    public function handle(Saving $event)
    {
        // 设置为默认用户组
        if ((bool) Arr::get($event->data, 'attributes.default', false)) {
            $event->group->default = true;

            $event->group->save();

            Group::query()->where('id', '<>', $event->group->id)->update(['default' => 0]);
        }
    }
}
