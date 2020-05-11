<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Users;

class RefreshTokend
{
    protected $tokenResponse;

    public function __construct($tokenResponse)
    {
        $this->tokenResponse = $tokenResponse;
    }
}
