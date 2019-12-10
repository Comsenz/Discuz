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

    public function __construct($id, $actor, $data)
    {
        $this->id = $id;
        $this->actor = $actor;
        $this->data = $data;
    }

    public function handle(GroupRepository $groups, GroupValidator $groupValidator) {
        return $this($groups, $groupValidator);
    }

    public function __invoke(GroupRepository $groups, GroupValidator $groupValidator)
    {
        $id = $this->id;
        $data = null;

        try {
            $group = $groups->findOrFail($id, $this->actor);

            $this->assertCan($this->actor, 'edit', $group);

            $group->name = Arr::get($this->data, 'attributes.name', '');
            $group->type = Arr::get($this->data, 'attributes.type', '');
            $group->color = Arr::get($this->data, 'attributes.color', '');
            $group->icon = Arr::get($this->data, 'attributes.icon', '');

            // 修改时调用脏数据Dirty
            $groupValidator->valid($group->getDirty());

            $group->save();

            $group->succeed = true;
            $data = $group;
        } catch (ValidationException $v) {
            throw $v;
        } catch (Exception $e) {
            $group = new Group();
            $group->id = $id;
            $group->error = $e->getMessage();
            $data = $group;
        }

        return $data;
    }
}
