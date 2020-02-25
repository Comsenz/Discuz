<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $payment_sn
 * @property int $user_id
 * @property string $trade_no
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @package App\Models
 */
class PayNotify extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 通知状态
     */
    const NOTIFY_STATUS_PENDING  = 0; //未收到通知

    /**
     * 通知状态
     */
    const NOTIFY_STATUS_RECEIVED = 1; //已收到通知

    /**
     * {@inheritdoc}
     */
    protected $table = 'pay_notify';

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
