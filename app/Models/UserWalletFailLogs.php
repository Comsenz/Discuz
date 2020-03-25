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
 * @property string $ip
 * @property int $user_id
 * @property Carbon $created_at
 * @package App\Models
 */
class UserWalletFailLogs extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'user_wallet_fail_logs';

    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * @param $ip
     * @param $user_id
     * @return bool
     */
    public static function build($ip, $user_id)
    {
        $log = new static();
        $log->ip = $ip;
        $log->user_id = $user_id;
        $log->created_at = Carbon::now();
        return $log->save();
    }
}
