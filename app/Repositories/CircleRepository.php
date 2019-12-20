<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use App\Models\Circle;
use Illuminate\Database\Eloquent\Model;

class CircleRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the posts table.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return Circle::query();
    }

    /**
     * Find a user by ID, optionally making sure it is visible to a certain
     * user, or throw an exception.
     *
     * @param int $id
     * @param User $actor
     * @param string $ability
     * @return \Illuminate\Database\Eloquent\Builder|Model
     *
     */
    public function findOrFail($id, User $actor = null, $ability = 'find')
    {
        $query = Circle::where('id', $id);

        return $this->scopeVisibleTo($query, $actor, $ability)->firstOrFail();
    }
}
