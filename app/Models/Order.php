<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Order.php xxx 2019-10-16 15:36 zhouzhou $
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Discuz\Database\ScopeVisibilityTrait;

/**
 * @property int $id
 * @property string $order_sn
 * @property string $payment_sn
 * @property float $amount
 * @property int $user_id
 * @property int $payee_id
 * @property int $type
 * @property int $thread_id
 * @property int $status
 * @property int $platform
 * @property int $payment_type
 * @property string $remark
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Thread $thread
 * @property User $user
 * @property User $payee
 * @package App\Models
 */
class Order extends Model
{
    use ScopeVisibilityTrait;
    
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
     * Define the relationship with the order's payee.
     *
     * @return belongsTo
     */
    public function payee()
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
     * Define the relationship with the order's thread.
     *
     * @return BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
