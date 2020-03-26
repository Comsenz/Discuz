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
    /**
     * 创建用户组时，设置默认权限
     *
     * @param Created $event
     */
    public function handle(Created $event)
    {
        $groupId = $event->group->id;

        $defaultPermission = collect([
            'thread.favorite',              // 收藏
            'thread.likePosts',             // 点赞
            'userFollow.create',            // 关注
            'order.create',                 // 创建订单
            'trade.pay.order',              // 支付订单
            'cash.create',                  // 提现
        ])->map(function ($item) use ($groupId) {
            return [
                'group_id' => $groupId,
                'permission' => $item,
            ];
        })->toArray();

        Permission::insert($defaultPermission);
    }
}
