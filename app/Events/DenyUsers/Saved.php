<?php


namespace App\Events\DenyUsers;


class Saved
{

    public $denyUser;

    public $actor;

    public function __construct($denyUser, $actor)
    {
        $this->denyUser = $denyUser;
        $this->actor = $actor;
    }

}
