<?php


namespace App\Events\Users;


class RefreshTokend
{
    protected $tokenResponse;

    public function __construct($tokenResponse)
    {
        $this->tokenResponse = $tokenResponse;
    }
}
