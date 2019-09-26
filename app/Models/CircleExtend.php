<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      1: CircleExtend.php28830 2019-09-26 17:59 chenkeke $
 */

namespace App\Models;

use App\Events\CircleExtend\Created;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property 圈子ID circle_id
 * @property 收费类型 type
 * @property 收费金额 price
 * @property 分成规则 share_rule
 * @property 创建的IP地址 ipAddress
 */
class CircleExtend extends Model
{
    use EventGeneratorTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'circle_extends';

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

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
     * 创建圈子扩展数据.
     *
     * @param $circle_id  圈子ID
     * @param $type       收费类型
     * @param $price      收费金额
     * @param $share_rule 分成规则
     * @param $ipAddress  创建的IP地址
     * @return static
     */
    public static function create($circle_id, $type, $price, $share_rule, $ipAddress)
    {
        // 实例一个圈子扩展模型
        $circleExtend = new static;

        // 设置模型属性值
        $circleExtend->circle_id = $circle_id;
        $circleExtend->type = $type;
        $circleExtend->price = $price;
        $circleExtend->share_rule = $share_rule;
        $circleExtend->ipAddress = $ipAddress;

        // 暂存需要执行的事件
        $circleExtend->raise(new Created($circleExtend));

        // 返回圈子扩展模型
        return $circleExtend;
    }
}