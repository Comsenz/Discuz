<?php


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
            Group::query()->update(['default' => 0]);

            $event->group->default = true;

            $event->group->save();
        }
    }
}
