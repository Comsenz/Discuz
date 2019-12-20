<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

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

    /**
     * 初始化命令参数
     * @param int     $id
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
        return call_user_func([$this, '__invoke'], $users);
    }

    /**
     * @param UserRepository $users
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function __invoke(UserRepository $users)
    {
        $user = $users->findOrFail($this->id, $this->actor);

        $this->assertCan($this->actor, 'delete', $user);

        $user->delete();
    }
}
