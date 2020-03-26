<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Group;

use App\Events\Group\Created;
use App\Models\Permission;

class SetDefaultPermission
{
    public function handle(Created $event)
    {
        $groupId = $event->group->id;

        // 默认权限：收藏、点赞、创建订单、支付订单、提现
        $defaultPermission = collect([
            'thread.favorite',
            'thread.likePosts',
            'order.create',
            'trade.pay.order',
            'cash.create',
        ])->map(function ($item) use ($groupId) {
            return [
                'group_id' => $groupId,
                'permission' => $item,
            ];
        })->toArray();

        Permission::insert($defaultPermission);
    }
}
