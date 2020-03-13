<?php


namespace App\Listeners\Group;


use App\Events\Group\Deleted;
use App\Models\Group;

class ResetDefaultGroup
{
    public function handle(Deleted $event)
    {
        // 如果被删除的是默认用户组，将默认用户组还原为 member group
        if ($event->group->default) {
            $group = Group::find(Group::MEMBER_ID);

            $group->default = true;

            $group->save();

            Group::query()->where('id', '<>', Group::MEMBER_ID)->update(['default' => 0]);
        }
    }
}
