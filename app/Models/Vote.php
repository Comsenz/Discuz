<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property int $thread_id
 * @property int $type
 * @property int $total_count
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
 */
class Vote extends Model
{
    //单选
    const TYPE_SINGLE = 0;

    //多选
    const TYPE_MULTIPLE = 1;

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'start_at',
        'end_at',
        'updated_at',
        'created_at',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

}
