<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Topic;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class TopicRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the topic table.
     *
     * @return Builder
     */
    public function query()
    {
        return Topic::query();
    }

    /**
     * Find a topic by ID
     *
     * @param int $id
     * @param User|null $actor
     * @return Builder|\Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id, User $actor = null)
    {
        $query = $this->query()->where('id', $id);

        return $query->firstOrFail();
    }
}
