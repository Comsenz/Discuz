<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Users;

use App\Models\User;

class UserFollowCount
{
    /**
     * @var User
     */
    public $fromUser;

    public $toUser;

    /**
     *
     * @param User $fromUser
     * @param User $toUser
     */
    public function __construct(User $fromUser, User $toUser)
    {
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
    }
}
