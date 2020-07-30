<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
