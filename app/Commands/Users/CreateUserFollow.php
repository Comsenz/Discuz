<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Models\User;
use App\Models\UserFollow;
use App\Repositories\UserRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;

class CreateUserFollow
{
    use AssertPermissionTrait;

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

    public function handle(UserFollow $userFollow, UserRepository $user)
    {
        $this->assertCan($this->actor, 'create', $userFollow);
        if ($this->actor->id == $this->to_user_id) {
            throw new PermissionDeniedException();
        }
        $user->findOrFail($this->to_user_id);

        $userFollow = $userFollow->firstOrCreate(['from_user_id'=>$this->actor->id,'to_user_id'=>$this->to_user_id]);

        return $userFollow;
    }
}
