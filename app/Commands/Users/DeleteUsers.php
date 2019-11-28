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


        $data = null;
        $id = $this->id;
        $actor = $this->actor;

        try {
            $user = $this->users->findOrFail($id);

            $this->assertCan($actor, 'delete', $user);

            $user->delete();

            $data = $user;
        } catch (\Exception $e) {
            $data = new User(compact('id'));
            $data->error = $e->getMessage();
        }

        return $data;
    }
}
