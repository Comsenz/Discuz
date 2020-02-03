<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Category;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class CategoryRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the categories table.
     *
     * @return Builder
     */
    public function query()
    {
        return Category::query();
    }

    /**
     * Find a category by ID, optionally making sure it is visible to a
     * certain user, or throw an exception.
     *
     * @param int $id
     * @param User|null $actor
     * @return Category
     */
    public function findOrFail($id, User $actor = null)
    {
        $query = Category::where('id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
