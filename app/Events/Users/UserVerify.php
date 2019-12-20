<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Users;

use App\Models\User;

class UserVerify
{
    public $user;

    public $data;

    public function __construct(User $user, array $data)
    {
        $this->user = $user;
        $this->data = $data;
    }
}
