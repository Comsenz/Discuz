<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Deleted.php xxx 2019-11-11 10:27:00 LiuDongdong $
 */

namespace App\Events\Thread;

use App\Models\Thread;
use App\Models\User;

class Deleted
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
     * @param Thread $thread
     * @param User $actor
     */
    public function __construct(Thread $thread, User $actor = null)
    {
        $this->thread = $thread;
        $this->actor = $actor;
    }
}
