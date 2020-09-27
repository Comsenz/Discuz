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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Discuz\Database\ScopeVisibilityTrait;

class GroupPaidUser extends Model
{

    use ScopeVisibilityTrait;
    use SoftDeletes;

    protected $fillable = ['delete_type'];

    /**
     * 删除类型，到期删除
     */
    const DELETE_TYPE_EXPIRE   = 1;

    /**
     * 删除类型，管理员修改
     */
    const DELETE_TYPE_ADMIN    = 2;

    /**
     * 删除类型，用户复购
     */
    const DELETE_TYPE_RENEW    = 3;

    public static function creation(
        $user_id,
        $group_id,
        $expiration_time,
        $order_id,
        $operator_id,
        $delete_type = 0
    ) {
        // 实例一个模型
        $group_paid_user = new static;

        $group_paid_user->user_id = $user_id;
        $group_paid_user->group_id = $group_id;
        $group_paid_user->expiration_time = $expiration_time;
        $group_paid_user->order_id = $order_id;
        $group_paid_user->operator_id = $operator_id;
        $group_paid_user->delete_type = $delete_type;
        // 返回模型
        return $group_paid_user;
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * @return BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(order::class, 'order_id');
    }

    /**
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(group::class, 'group_id');
    }
}
