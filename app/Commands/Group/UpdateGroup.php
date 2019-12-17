<?php


namespace App\Commands\Group;

use App\Models\Group;
use App\Repositories\GroupRepository;
use App\Validators\GroupValidator;
use Discuz\Auth\AssertPermissionTrait;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class UpdateGroup
{
    use AssertPermissionTrait;

    protected $id;
    protected $actor;
    protected $data;
    protected $groups;
    protected $validator;

    public function __construct($id, $actor, $data)
    {
        $this->id = $id;
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(GroupRepository $groups, GroupValidator $validator) {
        $this->groups = $groups;
        $this->validator = $validator;
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
        $group->default = Arr::get($this->data, 'attributes.default', 0);

        // 修改时调用脏数据Dirty
        $this->validator->valid($group->getDirty());

        $group->save();

        return $group;
    }
}
