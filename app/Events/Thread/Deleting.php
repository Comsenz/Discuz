<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Deleting.php xxx 2019-11-08 15:34:00 LiuDongdong $
 */

namespace App\Events\Thread;

use App\Models\Thread;
use App\Models\User;

class Deleting
{
    /**
     * The thread that is going to be deleted.
     *
     * @var Thread
     */
    public $thread;

    /**
     * The user who is performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * Any user input associated with the command.
     *
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
