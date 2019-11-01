<?php
declare(strict_types=1);


namespace App\Events\UserIdent;

use App\Models\UserIdent;

class Created
{
    /**
     * @var UserIdent
     */
    public $UserIdent;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param User $user
     * @param User   $actor
     */
    public function __construct(UserIdent $user, $actor = null)
    {
        $this->user = $user;
        $this->actor = $actor;
    }
}