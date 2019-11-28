<?php

namespace App\Commands\Users;


use App\Repositories\UserRepository;
use App\Models\User;
use Discuz\Auth\AssertPermissionTrait;

class DeleteUsers
{
    use AssertPermissionTrait;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    protected $actor;

    protected $id;

    protected $users;
    /**
     * 初始化命令参数
     * @param id     $id
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建用户的数据.
     */
    public function __construct($id, User $actor)
    {
        $this->id = $id;
        $this->actor = $actor;
    }

    public function handle(UserRepository $users)
    {
        $this->users = $users;
        return $this();
    }

    public function __invoke()
    {

        $user = $this->users->findOrFail($this->id);

        $this->assertCan($this->actor, 'delete', $user);

        $user->delete();

        return $user;
    }
}
