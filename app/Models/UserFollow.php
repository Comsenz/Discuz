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
 * @property int $from_user_id
 * @property int $to_user_id
 * @property Carbon $created_at
 * @package App\Models
 */
class UserFollow extends Model
{
    const UPDATED_AT = null;

    /**
     * {@inheritdoc}
     */
    protected $table = 'user_follow';

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'from_user_id',
        'to_user_id'
    ];
}
