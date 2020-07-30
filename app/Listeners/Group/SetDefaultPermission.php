<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
            'user.view',                    // 查看个人信息，目前仅用于前台显示权限
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
