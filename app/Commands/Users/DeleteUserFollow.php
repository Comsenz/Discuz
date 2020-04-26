<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Events\Users\UserFollowCount;
use App\Models\User;
use App\Models\UserFollow;
use App\Repositories\UserFollowRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

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

        $userFollowRes = $userFollowRepository->findOrFail($this->to_user_id, $this->from_user_id, $this->actor);

        $deleteRes = $userFollowRes->delete();

        //取消互相关注
        if ($this->to_user_id) {
            $toUserFollow = $userFollow->where(['to_user_id'=>$this->actor->id,'from_user_id'=>$this->to_user_id,'is_mutual'=>1])->first();
            $toUser = $user->findOrFail($this->to_user_id);
            $fromUser = $this->actor;
        } else {
            $toUserFollow = $userFollow->where(['to_user_id'=>$this->from_user_id,'from_user_id'=>$this->actor->id,'is_mutual'=>1])->first();
            $toUser = $this->actor;
            $fromUser = $user->findOrFail($this->from_user_id);
        }
        if ($toUserFollow) {
            $toUserFollow->is_mutual = 0;
            $toUserFollow->save();
        }

        $this->events->dispatch(
            new UserFollowCount($fromUser, $toUser)
        );

        return $deleteRes;
    }
}
