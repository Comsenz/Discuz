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

/**
 * @property int $group_id
 * @property string $permission
 */
class Permission extends Model
{
    const DEFAULT_PERMISSION = [
        'thread.favorite',              // 收藏
        'thread.likePosts',             // 点赞
        'userFollow.create',            // 关注
        'user.view',                    // 查看个人信息，目前仅用于前台显示权限
        'order.create',                 // 创建订单
        'trade.pay.order',              // 支付订单
        'cash.create',                  // 提现
    ];

    /**
     * {@inheritdoc}
     */
    protected $table = 'group_permission';

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['group_id', 'permission'];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * Define the relationship with the group that this permission is for.
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
