<?php

namespace App\Listeners\Group;

use App\Events\Group\Created;
use App\Models\Permission;

class SetDefaultPermission
{
    public function handle(Created $event)
    {
        $groupId = $event->group->id;

        // 默认权限：收藏、点赞、打赏
        $defaultPermission = collect([
            'thread.favorite',
            'thread.likePosts',
            'order.create'
        ])->map(function ($item) use ($groupId) {
            return [
                'group_id' => $groupId,
                'permission' => $item,
            ];
        })->toArray();

        Permission::insert($defaultPermission);
    }
}
