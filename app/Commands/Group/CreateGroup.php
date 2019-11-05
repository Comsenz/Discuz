<?php

namespace App\Commands\Group;

use App\Events\Group\Saving;
use App\Models\Group;
use App\Models\User;
use App\Validators\GroupValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreateGroup
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new group.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new group.
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param GroupValidator $validator
     * @return Group
     * @throws NotAuthenticatedException
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, GroupValidator $validator)
    {
        $this->events = $events;

        $this->assertRegistered($this->actor);
        $this->assertCan($this->actor, 'group.createGroup');

        $attributes = Arr::get($this->data, 'attributes', []);

        $group = new Group();

        $group->name = $attributes['name'];
        $group->type = $attributes['type'];
        $group->color = $attributes['color'];
        $group->icon = $attributes['icon'];

        $this->events->dispatch(
            new Saving($group, $this->actor, $this->data)
        );

        $validator->valid($group->getAttributes());

        $group->save();

        $this->dispatchEventsFor($group, $this->actor);

        return $group;
    }
}
