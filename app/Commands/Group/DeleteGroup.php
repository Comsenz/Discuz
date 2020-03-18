<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Group;

use App\Models\User;
use App\Repositories\GroupRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteGroup
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var User
     */
    protected $actor;

    /**
     * @param int $id
     * @param User $actor
     */
    public function __construct($id, User $actor)
    {
        $this->id = $id;
        $this->actor = $actor;
    }

    /**
     * @param GroupRepository $groups
     * @param Dispatcher $events
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function handle(GroupRepository $groups, Dispatcher $events)
    {
        $this->events = $events;

        $group = $groups->findOrFail($this->id, $this->actor);

        $this->assertCan($this->actor, 'delete', $group);

        $group->delete();

        $this->dispatchEventsFor($group, $this->actor);

        return $group;
    }
}
