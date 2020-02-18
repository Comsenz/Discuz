<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Dialog;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class DialogRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the attachments table.
     *
     * @return Builder
     */
    public function query()
    {
        return Dialog::query();
    }

    public function findOrFail($id, User $actor = null)
    {
        $query = $this->query();
        $query->where('id', $id);
        $query->where('sender_user_id', $actor->id);
        $query->orWhere('recipient_user_id', $actor->id);

        return $query->firstOrFail();
    }

}
