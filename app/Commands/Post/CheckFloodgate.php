<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Post;

use App\Models\Post;
use App\Models\User;
use DateTime;

class CheckFloodgate
{
    /**
     * @var User
     */
    public $actor;

    public function __construct(User $actor)
    {
        $this->actor = $actor;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        if ($this->isFlooding($this->actor)) {
            throw new \Exception('too_many_requests');
        }
    }

    /**
     * @param User $actor
     * @return bool
     * @throws \Exception
     */
    public function isFlooding(User $actor): bool
    {
        return Post::where('user_id', $actor->id)->where('created_at', '>=', new DateTime('-10 seconds'))->exists();
    }
}
