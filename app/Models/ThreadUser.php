<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadUser.php xxx 2019-11-13 16:55:00 LiuDongdong $
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Models a thread-user state record in the database.
 *
 * @property int $user_id
 * @property int $thread_id
 * @property Carbon|null $created_at
 * @property Thread $thread
 * @property User $user
 */
class ThreadUser extends Pivot
{

}
