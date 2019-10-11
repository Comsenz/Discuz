<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CircleRepository.php 28830 2019-09-25 11:45 chenkeke $
 */

namespace App\Repositories;

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
     * @return \Flarum\Group\Group
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, Model $actor = null)
    {
        $query = Circle::where('id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }


}