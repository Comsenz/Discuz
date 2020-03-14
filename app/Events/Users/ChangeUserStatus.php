<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Users;

class ChangeUserStatus
{
    public $actor;

    public $user;

    public $refuse;

    public function __construct($user, $refuse = null)
    {
        $this->user = $user;
        $this->refuse = $refuse;
    }
}
