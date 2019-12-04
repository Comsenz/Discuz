<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: OperationLog.php xxx 2019-11-25 15:27:00 LiuDongdong $
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $message
 * @property int $log_able_id
 * @property string $log_able_type
 * @property Carbon $created_at
 * @property User $user
 * @package App\Models
 */
class OperationLog extends Model
{
    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $table = 'operation_log';

    /**
     * {@inheritdoc}
     */
    protected $dates = ['created_at'];

    /**
     * @return MorphTo
     */
    public function logFor()
    {
        return $this->morphTo('log_able');
    }

    /**
     * Define the relationship with the log's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
