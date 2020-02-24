<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Models\User;
use App\Models\UserFollow;
use App\Events\Users\UserFollowCount;
use App\Repositories\UserRepository;
use Discuz\Auth\AssertPermissionTrait;
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

    public function handle(UserFollow $userFollow, UserRepository $user, Dispatcher $events)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'userFollow.create');
        if ($this->actor->id == $this->to_user_id) {
            throw new PermissionDeniedException();
        }
        $toUser = $user->findOrFail($this->to_user_id);

        //判断是否需要设置互相关注
        $toUserFollow = $userFollow->where(['from_user_id'=>$this->to_user_id,'to_user_id'=>$this->actor->id])->first();
        $is_mutual = 0;
        if ($toUserFollow) {
            $is_mutual = 1;
            $toUserFollow->is_mutual = 1;
            $toUserFollow->save();
        }

        $userFollow = $userFollow->firstOrCreate(
            ['from_user_id'=>$this->actor->id,'to_user_id'=>$this->to_user_id],
            ['is_mutual'=>$is_mutual]
        );


        $this->events->dispatch(
            new UserFollowCount($this->actor, $toUser)
        );


        return $userFollow;
    }
}
