<?php


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
