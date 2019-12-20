<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
    public function __construct(Thread $thread, User $actor = null)
    {
        $this->thread = $thread;
        $this->actor = $actor;
    }
}
