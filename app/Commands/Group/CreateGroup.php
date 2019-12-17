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
    protected $actor;

    /**
     * The attributes of the new group.
     *
     * @var array
     */
    protected $data;

    protected $validator;

    /**
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new group.
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }


    public function handle(Dispatcher $events, GroupValidator $validator)
    {
        $this->events = $events;
        $this->validator = $validator;

        return call_user_func([$this, '__invoke']);
    }

    /**
     * @return Group
     * @throws PermissionDeniedException
     */
    public function __invoke()
    {
        $this->assertCan($this->actor, 'create');

        $attributes = Arr::get($this->data, 'attributes', []);

        $group = new Group();

        $group->name = Arr::get($attributes, 'name');
        $group->type = Arr::get($attributes, 'type', '');
        $group->color = Arr::get($attributes, 'color', '');
        $group->icon = Arr::get($attributes, 'icon', '');
        $group->default = Arr::get($attributes, 'default', 0);

        $this->events->dispatch(
            new Saving($group, $this->actor, $this->data)
        );

        $this->validator->valid($group->getAttributes());

        $group->save();

        $this->dispatchEventsFor($group, $this->actor);

        return $group;
    }
}
