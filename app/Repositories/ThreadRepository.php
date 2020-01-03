<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Thread;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class ThreadRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the threads table.
     *
     * @return Builder
     */
    public function query()
    {
        return Thread::query();
    }

    /**
     * Find a thread by ID, optionally making sure it is visible to a
     * certain user, or throw an exception.
     *
     * @param int $id
     * @param User|null $actor
     * @return Thread
     */
    public function findOrFail($id, User $actor = null)
    {
        $query = Thread::where('id', $id);

        if ($actor && $actor->cannot('viewThreads')) {
            $query->whereRaw('FALSE');
        }

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
