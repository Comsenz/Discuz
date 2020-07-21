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

use App\Events\Group\PaidGroup;
use App\Models\GroupPaidUser;
use App\Models\Group;
use Illuminate\Support\Carbon;

/**
 * Class PaidGroupOrder
 * @package App\Listeners\Group
 * 成功购买用户组事件
 */
class PaidGroupOrder
{
    public function handle(PaidGroup $event)
    {
        if (isset($event->group_id)) {
            $user_group_ids = $event->user->groups()->pluck('id')->all();
            $group_info = Group::findOrFail($event->group_id);
            if (in_array($event->group_id, $user_group_ids)) {
                //已有用户组
                $delete_type = 0;
                $group_paid_user_info = GroupPaidUser::where('user_id', $event->user->id)->where('group_id', $event->group_id)->first();
                if (isset($group_paid_user_info->expiration_time)) {
                    if (!empty($event->operator->id)) {
                        //管理员操作时重新设置过期时间不变
                        $delete_type = GroupPaidUser::DELETE_TYPE_ADMIN;
                        $expiration_time = $group_paid_user_info->expiration_time;
                    } else {
                        //其他情况，到期时间往后顺延
                        $delete_type = GroupPaidUser::DELETE_TYPE_RENEW;
                        $expiration_time = Carbon::parse($group_paid_user_info->expiration_time)->addDay($group_info->days);
                    }
                    //软删除原记录
                    $group_paid_user_info->update(['delete_type' => $delete_type]);
                    $group_paid_user_info->delete();
                } else {
                    $expiration_time = Carbon::now()->addDay($group_info->days);
                }
                $group_paid_user = GroupPaidUser::creation(
                    $event->user->id,
                    $group_info->id,
                    $expiration_time,
                    isset($event->order->id) ? $event->order->id : 0,
                    isset($event->operator->id) ? $event->operator->id : null
                );
                //添加新记录
                $group_paid_user->save();
                //同时修改用户组到期时间
                $event->user->groups()->where('group_id', $event->group_id)->update(['expiration_time' => $expiration_time]);
            } else {
                //新增用户组
                $expiration_time = Carbon::now()->addDay($group_info->days);
                $event->user->groups()->attach($group_info->id, ['expiration_time' => $expiration_time]);
                $group_paid_user = GroupPaidUser::creation(
                    $event->user->id,
                    $group_info->id,
                    $expiration_time,
                    isset($event->order->id) ? $event->order->id : 0,
                    isset($event->operator->id) ? $event->operator->id : null
                );
                $group_paid_user->save();
            }
        }
    }
}
