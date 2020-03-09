<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property int $id
 * @property int $thread_id
 * @property int $user_id
 * @property string $file_id
 * @property string $media_url
 * @property string $cover_url
 * @property Carbon $updated_at
 * @property Carbon $created_at
 */
class ThreadVideo extends Model
{

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
