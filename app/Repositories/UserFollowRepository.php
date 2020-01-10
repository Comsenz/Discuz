<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\User;
use App\Models\UserFollow;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UserFollowRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the attachments table.
     *
     * @return Builder
     */
    public function query()
    {
        return UserFollow::query();
    }

    public function findOrFail($to_user_id, $from_user_id, User $actor = null)
    {
        $query = $this->query();

        if ($to_user_id) {
            $query->where(['to_user_id'=>$to_user_id,'from_user_id'=>$actor->id]);
        } else {
            $query->where(['to_user_id'=>$actor->id,'from_user_id'=>$from_user_id]);
        }

        return $query->firstOrFail();
    }
}
