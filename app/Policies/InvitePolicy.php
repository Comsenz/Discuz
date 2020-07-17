<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Policies;

use App\Models\Invite;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InvitePolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Invite::class;

    /**
     * @param User $actor
     * @param Invite $invite
     * @return bool|null
     */
    public function delete(User $actor, Invite $invite)
    {
        if ($actor->hasPermission('createInvite') && ($invite->user_id == $actor->id || $actor->isAdmin())) {
            return true;
        }
    }
}
