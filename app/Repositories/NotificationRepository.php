<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification;

class NotificationRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the posts table.
     *
     * @return Builder
     */
    public function query()
    {
        return DatabaseNotification::query();
    }

    /**
     * @param $id
     * @param User $actor
     * @return Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id, User $actor)
    {
        $query = $this->query()->where('id', $id)->where('notifiable_id', $actor->id);

        return $query->firstOrFail();
    }
}
