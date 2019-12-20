<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CircleExtend.php28830 2019-09-26 17:59 chenkeke $
 */

namespace App\Models;

use App\Events\CircleExtend\Created;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property 站点ID circle_id
 * @property 收费类型 type
 * @property 收费金额 price
 * @property 分成规则 share_rule
 * @property 创建的IP地址 ip
 * @property 分成规则 indate_type
 * @property 有效时间 indate_time
 * @property 加入站点站长分成比例 join_circle_ratio_master
 * @property 看帖站长分成比例 read_thread_ratio_master
 * @property 看帖站长分成比例 read_thread_ratio_admin
 * @property 打赏帖子站长分成比例 give_thread_ratio_master
 * @property 打赏帖子站长分成比例 give_thread_ratio_admin
 */
class CircleExtend extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

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
     * 创建站点扩展数据.
     *
     * @param $circle_id 站点ID
     * @param $type 收费类型
     * @param $price 收费金额
     * @param $indate_type 有效期类型
     * @param $indate_time 有效时间
     * @param $join_circle_ratio_master 加入站点站长分成比例
     * @param $read_thread_ratio_master 看帖站长分成比例
     * @param $read_thread_ratio_admin 看帖站长分成比例
     * @param $give_thread_ratio_master 打赏帖子站长分成比例
     * @param $give_thread_ratio_admin 打赏帖子站长分成比例
     * @param $ipAddress 创建的IP地址
     * @return static
     */
    public static function creation(
        $circle_id,
        $type,
        $price,
        $indate_type,
        $indate_time,
        $join_circle_ratio_master,
        $read_thread_ratio_master,
        $read_thread_ratio_admin,
        $give_thread_ratio_master,
        $give_thread_ratio_admin,
        $ipAddress
    ) {
        // 实例一个站点扩展模型
        $circleExtend = new static;

        // 设置模型属性值
        $circleExtend->circle_id = $circle_id;
        $circleExtend->type = $type;
        $circleExtend->price = $price;
        $circleExtend->indate_type = $indate_type;
        $circleExtend->indate_time = $indate_time;
        $circleExtend->join_circle_ratio_master = $join_circle_ratio_master;
        $circleExtend->read_thread_ratio_master = $read_thread_ratio_master;
        $circleExtend->read_thread_ratio_admin = $read_thread_ratio_admin;
        $circleExtend->give_thread_ratio_master = $give_thread_ratio_master;
        $circleExtend->give_thread_ratio_admin = $give_thread_ratio_admin;
        $circleExtend->ip = $ipAddress;

        // 暂存需要执行的事件
        $circleExtend->raise(new Created($circleExtend));

        // 返回站点扩展模型
        return $circleExtend;
    }
}