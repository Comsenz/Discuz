<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CircleUser.php 28830 2019-10-11 12:00 chenkeke $
 */

namespace App\Models;


use App\Events\Circle\Created;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class CircleUser extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'circle_users';

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
     * 创建站点用户.
     *
     * @param $name        站点名称
     * @param $icon        站点图标
     * @param $description 站点介绍
     * @param $property    站点属性
     * @param $ipAddress   创建的IP地址
     * @return static
     */
    public static function creation(
        $name,
        $icon,
        $description,
        $property,
        $ipAddress
    ) {
        // 实例一个站点模型
        $circle = new static;

        // 设置模型属性值
        $circle->name = $name;
        $circle->icon = $icon;
        $circle->description = $description;
        $circle->property = $property;
        $circle->ip = $ipAddress;

        // 暂存需要执行的事件
        $circle->raise(new Created($circle));

        // 返回站点模型
        return $circle;
    }

}