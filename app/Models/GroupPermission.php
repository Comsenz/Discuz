<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: GroupPermission.php 28830 2019-10-23 11:11 chenkeke $
 */

namespace App\Models;


use App\Events\GroupPermission\Created;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property 用户组ID group_id
 * @property 权限名称 permission
 * @method truncate()
 * @method insert(array $array)
 */
class GroupPermission extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'group_permission';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['group_id', 'permission'];

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;

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
     * 创建用户组权限.
     *
     * @param $group_id 用户组ID
     * @param $permission 权限名称
     * @return static
     */
    public static function creation(
        $group_id,
        $permission
    ) {
        // 实例一个模型
        $groupPermission = new static;

        // 设置模型属性值
        $groupPermission->group_id = $group_id;
        $groupPermission->permission = $permission;

        // 暂存需要执行的事件
        $groupPermission->raise(new Created($groupPermission));

        // 返回模型
        return $groupPermission;
    }

}
