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

use App\Events\Invite\Created;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $group_id
 * @property int $type
 * @property string $code
 * @property int $dateline
 * @property int $endtime
 * @property int $user_id
 * @property int $to_user_id
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Group $group
 * @package App\Models
 */
class Invite extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    const TYPE_GENERAL = 1;     // 普通会员邀请
    const TYPE_ADMIN = 2;       // 管理员邀请
    const STATUS_INVALID = 0;   // 失效
    const STATUS_UNUSED = 1;    // 未使用
    const STATUS_USED = 2;      // 已使用
    const STATUS_EXPIRED = 3;   // 已过期

    const INVITE_GROUP_LENGTH = 32; // 邀请指定用户组code码长度

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'invites';

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'group_id',
        'type',
        'code',
        'dateline',
        'endtime',
        'user_id',
        'to_user_id',
        'status',
    ];

    /**
     * 模型的「启动」方法.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
    }

    /**
     * Create a new user distribute
     *
     * @param array $attributes
     * @return static
     */
    public static function creation(array $attributes)
    {
        $invite = new static;

        $invite->fill($attributes);

        // 暂存需要执行的事件
        $invite->raise(new Created($invite));

        return $invite;
    }

    /**
     * 判断是否是32位 上下级邀请类型
     *
     * @param $code
     * @return bool
     */
    public static function lengthByAdmin($code)
    {
        $len = mb_strlen($code, 'utf-8');

        return $len == self::INVITE_GROUP_LENGTH;
    }

    /*
    |--------------------------------------------------------------------------
    | 关联模型
    |--------------------------------------------------------------------------
    */

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
