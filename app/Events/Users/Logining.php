<?php

namespace App\Events\Users;

use App\Models\User;

class Logining
{
    public $user;
    public $username;
    public $password;

    public function __construct(User $user, $username, $password)
    {
        $this->user = $user;
        $this->username = $username;
        $this->password = $password;
    }

}
