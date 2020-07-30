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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $message
 * @property int $log_able_id
 * @property string $log_able_type
 * @property Carbon $created_at
 * @property User $user
 * @package App\Models
 */
class UserActionLogs extends Model
{
    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $dates = ['created_at'];

    /**
     * 状态改变
     * (键要对应数据库)
     * @var array
     */
    public static $behavior = [
        0 => 'disapprove',  // 放入待审核
        1 => 'approve',     // 审核通过
        2 => 'ignore',      // 审核忽略
    ];

    /**
     * 写入操作日志
     *
     * @param User $actor
     * @param Model $model
     * @param string $action
     * @param string $message
     */
    public static function writeLog(User $actor, Model $model, string $action, string $message = '')
    {
        $log = new static;

        $log->user_id = $actor->id;
        $log->action = $action;
        $log->message = $message;
        $log->created_at = Carbon::now();

        $model->logs()->save($log);
    }

    /**
     * @return MorphTo
     */
    public function logFor()
    {
        return $this->morphTo('log_able');
    }

    /**
     * Define the relationship with the log's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
