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

use App\Events\Users\UserFollowCreated;
use App\Models\User;
use App\Models\UserFollow;
use App\Repositories\UserRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class CreateUserFollow
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var int
     */
    public $to_user_id;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     * @param int $to_user_id
     */
    public function __construct(User $actor, $to_user_id)
    {
        $this->actor = $actor;
        $this->to_user_id = $to_user_id;
    }

    /**
     * @param UserFollow $userFollow
     * @param UserRepository $user
     * @param Dispatcher $events
     * @return mixed
     * @throws PermissionDeniedException
     * @throws NotAuthenticatedException
     */
    public function handle(UserFollow $userFollow, UserRepository $user, Dispatcher $events)
    {
        $this->events = $events;

        $this->assertRegistered($this->actor);
        $this->assertCan($this->actor, 'userFollow.create');
        if ($this->actor->id == $this->to_user_id) {
            throw new PermissionDeniedException();
        }
        $toUser = $user->findOrFail($this->to_user_id);

        //在黑名单中，不能创建会话
        if (in_array($this->actor->id, array_column($toUser->deny->toArray(), 'id'))) {
            throw new PermissionDeniedException('user_deny');
        }

        //判断是否需要设置互相关注
        $toUserFollow = $userFollow->where(['from_user_id'=>$this->to_user_id,'to_user_id'=>$this->actor->id])->first();
        $is_mutual = UserFollow::NOT_MUTUAL;
        if ($toUserFollow) {
            $is_mutual = UserFollow::MUTUAL;
            $toUserFollow->is_mutual = $is_mutual;
            $toUserFollow->save();
        }

        $userFollow = $userFollow->firstOrCreate(
            ['from_user_id'=>$this->actor->id,'to_user_id'=>$this->to_user_id],
            ['is_mutual'=>$is_mutual]
        );

        $this->events->dispatch(
            new UserFollowCreated($this->actor, $toUser)
        );

        return $userFollow;
    }
}
