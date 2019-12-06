<?php


namespace App\Commands\Group;


use App\Models\Group;
use App\Repositories\GroupRepository;
use Discuz\Auth\AssertPermissionTrait;
use Exception;

class DeleteGroup
{
    use AssertPermissionTrait;

    protected $id;
    protected $actor;

    public function __construct($id, $actor)
    {
        $this->id = $id;
        $this->actor = $actor;
    }

    public function handle(GroupRepository $groups) {
        return $this($groups);
    }

    public function __invoke(GroupRepository $groups)
    {
        $id = $this->id;
        $data = null;
        try {
            $group = $groups->findOrFail($id, $this->actor);

            $this->assertCan($this->actor, 'delete', $group);

            $group->delete();

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
