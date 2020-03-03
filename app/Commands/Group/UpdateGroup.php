<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Group;

use App\Events\Group\Saving;
use App\Models\Group;
use App\Repositories\GroupRepository;
use App\Validators\GroupValidator;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class UpdateGroup
{
    use AssertPermissionTrait;

    protected $id;

    protected $actor;

    protected $data;

    protected $groups;

    protected $validator;

    protected $event;

    public function __construct($id, $actor, $data)
    {
        $this->id = $id;
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(GroupRepository $groups, GroupValidator $validator, Dispatcher $event)
    {
        $this->groups = $groups;
        $this->validator = $validator;
        $this->event = $event;
        return call_user_func([$this, '__invoke']);
    }

    /**
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function __invoke()
    {
        $group = $this->groups->findOrFail($this->id, $this->actor);

        $this->assertCan($this->actor, 'edit', $group);

        $group->name = Arr::get($this->data, 'attributes.name', '');
        $group->type = Arr::get($this->data, 'attributes.type', '');
        $group->color = Arr::get($this->data, 'attributes.color', '');
        $group->icon = Arr::get($this->data, 'attributes.icon', '');

        $this->validator->valid($group->getDirty());

        $this->event->dispatch(
            new Saving($group, $this->actor, $this->data)
        );

        $group->save();

        return $group;
    }
}
