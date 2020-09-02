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
use App\Repositories\UserFollowRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteUserFollow
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var int
     */
    public $to_user_id;

    /**
     * @var int
     */
    public $from_user_id;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     * @param $to_user_id
     * @param $from_user_id
     */
    public function __construct(User $actor, $to_user_id, $from_user_id)
    {
        $this->to_user_id = $to_user_id;
        $this->from_user_id = $from_user_id;
        $this->actor = $actor;
    }

    public function handle(UserFollow $userFollow, UserFollowRepository $followRepository, User $user, Dispatcher $events)
    {
        return call_user_func([$this, '__invoke'], $userFollow, $followRepository, $user, $events);
    }

    public function __invoke(UserFollow $userFollow, UserFollowRepository $userFollowRepository, User $user, Dispatcher $events)
    {
        $this->events = $events;
        $this->assertRegistered($this->actor);

        try {
            $userFollowRes = $userFollowRepository->findOrFail($this->to_user_id, $this->from_user_id, $this->actor);
        } catch (ModelNotFoundException $e) {
            //关注关系不存在等于删除成功
            return true;
        }

        $deleteRes = $userFollowRes->delete();

        //取消互相关注
        if ($this->to_user_id) {
            $toUserFollow = $userFollow->where(['to_user_id'=>$this->actor->id,'from_user_id'=>$this->to_user_id,'is_mutual'=>UserFollow::MUTUAL])->first();
            $toUser = $user->findOrFail($this->to_user_id);
            $fromUser = $this->actor;
        } else {
            $toUserFollow = $userFollow->where(['to_user_id'=>$this->from_user_id,'from_user_id'=>$this->actor->id,'is_mutual'=>UserFollow::MUTUAL])->first();
            $toUser = $this->actor;
            $fromUser = $user->findOrFail($this->from_user_id);
        }
        if ($toUserFollow) {
            $toUserFollow->is_mutual = UserFollow::NOT_MUTUAL;
            $toUserFollow->save();
        }

        $this->events->dispatch(
            new UserFollowCreated($fromUser, $toUser)
        );

        return $deleteRes;
    }
}
