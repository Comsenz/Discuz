<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Order.php xxx 2019-10-16 15:36 zhouzhou $
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    /**
     * 订单类型
     */
    const ORDER_TYPE_REGISTER = 1; //注册
    const ORDER_TYPE_REWARD   = 2; //打赏

    /**
     * 订单状态
     */
    const ORDER_STATUS_PENDING = 0; //待付款
    const ORDER_STATUS_PAID    = 1; //已付款
    const ORDER_STATUS_CANCEL  = 2; //取消订单

    /**
     * 注册收款人ID
     */
    const REGISTER_PAYEE_ID = 0;

    /**
     * Define the relationship with the order's owner.
     *
     * @return belongsTo
     */
    public function user()
    {

        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the order's pay_notify.
     *
     * @return hasOne
     */
    public function payNotify()
    {
        return $this->hasOne(PayNotify::class, 'payment_sn', 'payment_sn');
    }


    /**
     * @param $orders
     * @return Relationship
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
