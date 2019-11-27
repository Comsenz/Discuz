<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Hidden.php xxx 2019-11-27 14:27:00 LiuDongdong $
 */

namespace App\Events\Thread;

use App\Models\Thread;
use App\Models\User;

class Hidden
{
    /**
     * The thread that was hidden.
     *
     * @var Thread
     */
    public $thread;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var array
     */
    public $data;

    /**
     * @param Thread $thread
     * @param User $actor
     * @param array $data
     */
    public function __construct(Thread $thread, User $actor, array $data = [])
    {
        $this->thread = $thread;
        $this->actor = $actor;
        $this->data = $data;
    }
}
