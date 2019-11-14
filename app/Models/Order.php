<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Order.php xxx 2019-10-16 15:36 zhouzhou $
 */

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 订单类型
     */
    const ORDER_TYPE_REGISTER = 1;//注册
    const ORDER_TYPE_REWARD = 2;//打赏

    /**
     * 订单状态
     */
    const ORDER_STATUS_PENDING = 0;//待付款
    const ORDER_STATUS_PAID = 1;//已付款
    const ORDER_STATUS_CANCEL = 2;//取消订单

    /**
     * 模型的「启动」方法.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
    }
}
