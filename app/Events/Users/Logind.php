<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Users;

use App\Models\User;

class Logind
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
