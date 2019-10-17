<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Created.php xxx 2019-10-10 12:00:00 LiuDongdong $
 */

namespace App\Events\Thread;

use App\Models\Thread;
use App\Models\User;

class Created
{
    /**
     * @var Thread
     */
    public $thread;

    /**
     * @var User
     */
    public $actor;

    /**
     * Created constructor.
     *
     * @param Thread $thread
     * @param User $actor
     */
    public function __construct(Thread $thread, $actor = null)
    {
        // TODO: User $actor
        $this->thread = $thread;
        $this->actor = $actor;
    }
}
