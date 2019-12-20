<?php
declare(strict_types = 1);

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class PayNotify extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'pay_notify';

    /**
     * 通知状态
     */
    const NOTIFY_STATUS_PENDING  = 0; //未收到通知

    const NOTIFY_STATUS_RECEIVED = 1; //已收到通知

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Define the relationship with the pay_notify's order.
     *
     * @return belongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'payment_sn', 'payment_sn');
    }
}
