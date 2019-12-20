<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Invite;

use App\Models\User;
use App\Repositories\InviteRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;

class DeleteInvite
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the invite to delete.
     *
     * @var int
     */
    public $inviteId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * DeleteInvite constructor.
     * @param $inviteId
     * @param User $actor
     * @param array $data
     */
    public function __construct($inviteId, User $actor, array $data = [])
    {
        $this->inviteId = $inviteId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param InviteRepository $inviteRepository
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws PermissionDeniedException
     */
    public function handle(InviteRepository $inviteRepository)
    {
        $invite = $inviteRepository->findOrFail($this->inviteId, $this->actor);

        //$this->assertCan($this->actor, 'delete', $invite);
        $invite->status = 0;
        $invite->save();

        return $invite;
    }
}
