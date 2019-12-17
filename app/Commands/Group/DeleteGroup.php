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
        return call_user_func([$this, '__invoke'], $groups);
    }

    /**
     * @param GroupRepository $groups
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws Exception
     */
    public function __invoke(GroupRepository $groups)
    {
        $group = $groups->findOrFail($this->id, $this->actor);

        $this->assertCan($this->actor, 'delete', $group);

        $group->delete();
    }
}
