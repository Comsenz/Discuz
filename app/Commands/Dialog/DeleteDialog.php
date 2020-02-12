<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Dialog;

use App\Events\Users\UserFollowCount;
use App\Models\Dialog;
use App\Models\User;
use App\Models\UserFollow;
use App\Repositories\UserFollowRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteDialog
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var int
     */
    public $id;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     * @param $id
     */
    public function __construct(User $actor, $id)
    {
        $this->id = $id;
        $this->actor = $actor;
    }

    public function handle(Dialog $dialog, User $user, Dispatcher $events)
    {
        return call_user_func([$this, '__invoke'], $dialog, $user, $events);
    }

    public function __invoke(Dialog $dialog, User $user, Dispatcher $events)
    {
        $this->events = $events;



        return ;
    }
}
