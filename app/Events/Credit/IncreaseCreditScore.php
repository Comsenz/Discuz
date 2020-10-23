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

    public $isIncrease;

    /**
     * @param string $action
     * @param User $actor
     * @param bool $isIncrease
     */
    public function __construct($action, User $actor, bool $isIncrease = true)
    {
        $this->actor = $actor;
        $this->action = $action;
        $this->isIncrease = $isIncrease;
    }
}
