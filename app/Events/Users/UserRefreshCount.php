<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Users;

use App\Models\User;

class UserRefreshCount
{
    /**
     * The user that will be saved.
     *
     * @var User
     */
    public $user;

    /**
     * @param User $user The user that will be saved.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
