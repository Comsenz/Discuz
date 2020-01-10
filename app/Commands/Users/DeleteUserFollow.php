<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Models\User;
use App\Models\UserFollow;
use App\Repositories\UserFollowRepository;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteUserFollow
{
    use AssertPermissionTrait;

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

    public function handle(UserFollow $userFollow, UserFollowRepository $followRepository)
    {
        return call_user_func([$this, '__invoke'], $userFollow, $followRepository);
    }

    public function __invoke(UserFollow $userFollow, UserFollowRepository $userFollowRepository)
    {
        $userFollow = $userFollowRepository->findOrFail($this->to_user_id, $this->from_user_id, $this->actor);

        return $userFollow->delete();
    }
}
