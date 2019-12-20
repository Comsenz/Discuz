<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Models a post-user state record in the database.
 *
 * @property int $user_id
 * @property int $thread_id
 * @property Carbon|null $created_at
 * @property Thread $thread
 * @property User $user
 */
class PostUser extends Pivot
{
    public function likedUsers()
    {
        return $this->hasOne(User::class);
    }
}
