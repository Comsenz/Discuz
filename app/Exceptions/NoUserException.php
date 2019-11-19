<?php


namespace App\Exceptions;


use Exception;

class NoUserException extends Exception
{

    protected $user;

    public function setUser($user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }
}
