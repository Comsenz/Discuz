<?php


namespace App\Events\Credit;


use App\Models\User;

/**
 * Class IncreaseCreditScore
 * @package App\Events\Credit
 */
class IncreaseCreditScore
{
    public $action;
    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param string $action
     * @param User $actor
     */
    public function __construct($action, User $actor)
    {
        $this->actor = $actor;
        $this->action = $action;
    }
}
