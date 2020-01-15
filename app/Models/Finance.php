<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property float $income
 * @property float $withdrawal
 * @property int $order_count
 * @property float $order_profit
 * @property float $total_profit
 * @property float $register_profit
 * @property float $master_portion
 * @property float $withdrawal_profit
 * @property Carbon $created_at
 * @package App\Models
 */
class Finance extends Model
{
    const UPDATED_AT = null;

    const TYPE_DAYS = 1;   //统计方式（日）

    const TYPE_WEEKS = 2;  //统计方式（周）

    const TYPE_MONTH = 3;  //统计方式（月）

    /**
     * {@inheritdoc}
     */
    protected $table = 'finance';

    /**
     * {@inheritdoc}
     */
    protected $dates = ['created_at'];

    protected $dateFormat = 'Y-m-d';

}
