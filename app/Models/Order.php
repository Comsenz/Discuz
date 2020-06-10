<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Closure;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $order_sn
 * @property string $payment_sn
 * @property float $amount
 * @property float $master_amount
 * @property float $actual_amount
 * @property int $user_id
 * @property int $payee_id
 * @property int $type
 * @property int $thread_id
 * @property int $group_id
 * @property int $status
 * @property int $platform
 * @property int $payment_type
 * @property int $is_anonymous
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

    const ORDER_TYPE_THREAD   = 3; //付费主题

    const ORDER_TYPE_GROUP    = 4; //付费用户组

    /**
     * 订单状态
     */
    const ORDER_STATUS_PENDING = 0; //待付款

    const ORDER_STATUS_PAID    = 1; //已付款

    const ORDER_STATUS_CANCEL  = 2; //取消订单

    const ORDER_STATUS_FAILED  = 3; //支付失败

    const ORDER_STATUS_EXPIRED = 4; //订单已过期

    /**
     * 注册收款人ID
     */
    const REGISTER_PAYEE_ID = 0;


    /**
     * 付款方式
     */
    const PAYMENT_TYPE_WECHAT_NATIVE = 10; //微信扫码支付

    const PAYMENT_TYPE_WECHAT_WAP    = 11; //微信h5支付

    const PAYMENT_TYPE_WECHAT_JS     = 12; //微信网页、公众号

    const PAYMENT_TYPE_WECHAT_MINI   = 13; //微信小程序支付

    const PAYMENT_TYPE_WALLET        = 20;//钱包支付

    /**
     * 订单过期时间，单位分钟，订单过期后无法支付
     */
    const ORDER_EXPIRE_TIME          = 10;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'master_amount' => 'decimal:2',
        'type' => 'integer',
        'status' => 'integer',
        'is_anonymous' => 'boolean',
    ];

    /**
     * 订单类型
     * 1：注册，2：打赏，3：付费主题，4：付费用户组
     *
     * @var array
     */
    public static $enumType = [
        1 => '注册',
        2 => '打赏',
        3 => '付费主题',
        4 => '付费用户组',
    ];

    /**
     * 订单类型 - 枚举
     *
     * @param $mixed
     * @param mixed $default 枚举值/闭包
     * @return bool|false|int|mixed|string|callback
     */
    public static function enumType($mixed, $default = null)
    {
        $enum = static::$enumType;

        if (is_numeric($mixed)) {
            if ($bool = array_key_exists($mixed, $enum)) {
                // 获取对应value值
                $trans = $enum[$mixed];
            }
        } elseif (is_string($mixed)) {
            if ($bool = in_array($mixed, $enum)) {
                // 获取对应key值
                $trans = array_search($mixed, $enum);
            }
        } else {
            return false;
        }

        if (!isset($trans)) {
            return false;
        }

        if (empty($default)) {
            $result = $trans;
        } elseif ($default instanceof Closure) {
            $result = $default(['key' => $mixed, 'value' => $trans, 'bool' => $bool]);
        } else {
            $result = $bool;
        }

        return $result;
    }

    /**
     * 获取实际金额
     *
     * @return float
     */
    public function getActualAmountAttribute()
    {
        return number_format($this->amount - $this->master_amount, 2, '.', '');
    }

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

    /**
     * Define the relationship with the order's thread.
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
