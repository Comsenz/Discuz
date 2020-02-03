<?php


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
