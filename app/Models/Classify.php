<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Classifyp 28830 2019-10-14 10:17 chenkeke $
 */

namespace App\Models;


use App\Events\Classify\Created;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property 分类名称 name
 * @property 分类说明 description
 * @property 分类图标 icon
 * @property 分类排序 sort
 * @property 分类属性 property
 * @property 分类创建的IP ip
 */
class Classify extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'classify';

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
     * @param $name 分类名称
     * @param $description 分类说明
     * @param $icon 分类图标
     * @param $sort 分类排序
     * @param $property 分类属性
     * @param $ip 分类创建的IP
     * @return static
     */
    public static function creation(
        $name,
        $description,
        $icon,
        $sort,
        $property,
        $ip
    ) {
        // 实例一个模型
        $classify = new static;

        // 设置模型属性值
        $classify->name = $name;
        $classify->description = $description;
        $classify->icon = $icon;
        $classify->sort = $sort;
        $classify->property = $property;
        $classify->ip = $ip;

        // 暂存需要执行的事件
        $classify->raise(new Created($classify));

        // 返回模型
        return $classify;
    }

}