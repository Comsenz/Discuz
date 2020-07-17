<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Vote;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class VoteRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the topic table.
     *
     * @return Builder
     */
    public function query()
    {
        return Vote::query();
    }

    /**
     * Find a topic by ID
     *
     * @param int $id
     * @return Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        $query = $this->query()->where('id', $id);

        return $query->firstOrFail();
    }
}
