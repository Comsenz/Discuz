<?php


namespace App\Commands\Group;

use App\Models\Group;
use App\Repositories\GroupRepository;
use App\Validators\GroupValidator;
use Discuz\Auth\AssertPermissionTrait;
use Exception;
use Illuminate\Support\Arr;

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

    public function __invoke(GroupRepository $groups,GroupValidator $groupValidator)
    {
        $id = $this->id;
        $data = null;

        try {
            $group = $groups->findOrFail($id, $this->actor);

            $this->assertCan($this->actor, 'edit', $group);

            $attributes = Arr::get($this->data, 'attributes', []);

            $groupValidator->valid($attributes);

            $group->name = $attributes['name'];

            $group->save();

            $group->succeed = true;
            $data = $group;
        } catch (Exception $e) {
            $group = new Group();
            $group->id = $id;
            $group->error = $e->getMessage();
            $data = $group;
        }

        return $data;
    }
}
