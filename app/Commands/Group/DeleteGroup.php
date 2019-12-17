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
    protected $groups;

    public function __construct($id, $actor)
    {
        $this->id = $id;
        $this->actor = $actor;
    }

    public function handle(GroupRepository $groups) {
        $this->groups = $groups;
        return call_user_func([$this, '__invoke']);
    }


    /**
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function __invoke()
    {
        $group = $this->groups->findOrFail($this->id, $this->actor);

        $this->assertCan($this->actor, 'delete', $group);

        $group->delete();
    }
}
