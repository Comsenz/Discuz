<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
     * 操作行为
     * @var array
     */
    public static $actionType = [
        // 用户操作行为
        'user' => [
            'normal',      // 启用
            'ban',         // 禁用
            'mod',         // 审核中
            'through',     // 审核通过
            'refuse',      // 审核拒绝
            'ignore',      // 审核忽略
        ],
        // 主题操作行为
        'thread' => [
            'create',      // 创建
            'hide',        // 放入回收站
            'restore',     // 还原
            'revise',      // 修改内容
        ],
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
     * get array [behavior]
     *
     * @return array
     */
    public static function behavior()
    {
        return self::$behavior;
    }

    /**
     * get array [actionType]
     *
     * @param string $type
     * @return array
     */
    public static function getAction(string $type)
    {
        return self::$actionType[$type];
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
