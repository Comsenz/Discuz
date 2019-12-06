<?php

namespace App\Commands\Users;


use App\Repositories\UserRepository;
use App\Models\User;
use Discuz\Auth\AssertPermissionTrait;
use Exception;

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
        return $this($users);
    }

    /**
     * @param $users
     * @return User|null
     */
    public function __invoke($users)
    {

        $data = null;
        $id = $this->id;
        $actor = $this->actor;

        try {
            $user = $users->findOrFail($id);

            $this->assertCan($actor, 'delete', $user);

            $user->delete();

            $user->succeed = true;

            $data = $user;
        } catch (Exception $e) {
            $data = new User(compact('id'));
            $data->error = $e->getMessage();
        }

        return $data;
    }
}
