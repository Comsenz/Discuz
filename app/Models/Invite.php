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
 * @package App\Models
 */
class Invite extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    const TYPE_GENERAL = 1; //普通会员邀请

    const TYPE_ADMIN = 2;   //管理员邀请

    const STATUS_INVALID = 0;  //失效

    const STATUS_UNUSED = 1;   //未使用

    const STATUS_USED = 2;     //已使用

    const STATUS_EXPIRED = 3;   //已过期

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
     * 创建邀请码.
     *
     * @param $user_group_id
     * @param $code
     * @param $dateline
     * @param $endtime
     * @param $user_id
     * @return static
     */
    public static function creation(
        $group_id,
        $type,
        $code,
        $dateline,
        $endtime,
        $user_id
    ) {
        // 实例一个模型
        $invite = new static;

        // 设置模型属性值
        $invite->group_id = $group_id;
        $invite->type = $type;
        $invite->code = $code;
        $invite->dateline = $dateline;
        $invite->endtime = $endtime;
        $invite->user_id = $user_id;

        // 暂存需要执行的事件
        $invite->raise(new Created($invite));

        // 返回模型
        return $invite;
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
