<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Events\Invite\Created;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

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
