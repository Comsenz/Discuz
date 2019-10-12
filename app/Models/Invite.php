<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Invite.php 28830 2019-10-12 15:36 chenkeke $
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
     * 模型的日期字段的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const CREATED_AT = 'createtime';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const UPDATED_AT = 'updatetime';

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
        $user_group_id,
        $code,
        $dateline,
        $endtime,
        $user_id
    ) {
        // 实例一个模型
        $invite = new static;

        // 设置模型属性值
        $invite->user_group_id = $user_group_id;
        $invite->code = $code;
        $invite->dateline = $dateline;
        $invite->endtime = $endtime;
        $invite->user_id = $user_id;
        $invite->to_user_id = 0;
        $invite->status = 0;

        // 暂存需要执行的事件
        $invite->raise(new Created($invite));

        // 返回模型
        return $invite;
    }

}