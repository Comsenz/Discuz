<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait UserTrait
{
    /**
     * user list filters.
     *
     * @param Builder $query
     * @param array $filter
     */
    private function applyFilters(Builder $query, array $filter)
    {
        // 多个/单个 用户id
        if ($ids = Arr::get($filter, 'id')) {
            $ids = explode(',', $ids);
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        // 用户名
        if ($username = Arr::get($filter, 'username')) {
            // 多个用户名用逗号隔开
            $username = explode(',', $username);

            $query->where(function ($query) use ($username) {
                foreach ($username as $name) {
                    // 用户名前后存在星号（*）则使用模糊查询
                    if (Str::startsWith($name, '*') || Str::endsWith($name, '*')) {
                        $name = Str::replaceLast('*', '%', Str::replaceFirst('*', '%', $name));

                        $query->orWhere('username', 'like', $name);
                    } else {
                        $query->orWhere('username', $name);
                    }
                }
            });
        }

        // 手机号
        if ($mobile = Arr::get($filter, 'mobile')) {
            $query->where('mobile', $mobile);
        }

        // 状态
        if ($status = Arr::get($filter, 'status')) {
            $statusNum = User::enumStatus($status);
            $query->where('status', $statusNum);
        }

        // 用户组
        if ($group_id = Arr::get($filter, 'group_id')) {
            $query->join('group_user', 'users.id', '=', 'group_user.user_id')
                ->whereIn('group_id', $group_id);
        }

        // 是否绑定微信
        if ($weChat = Arr::get($filter, 'wechat')) {
            if ($weChat == 'yes') {
                $query->has('wechat');
            } elseif ($weChat == 'no') {
                $query->doesntHave('wechat');
            }
        }
    }
}
