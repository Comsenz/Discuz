<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Models a thread-topic state record in the database.
 *
 * @property int $id
 * @property int $thread_id
 * @property int $topic_id
 * @property Carbon|null $created_at
 * @property Thread $thread
 * @property Topic $topic
 */
class ThreadTopic extends Pivot
{

    const UPDATED_AT = null;

    public $incrementing = true;

}
