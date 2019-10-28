<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ClassifyRepository.php 28830 2019-10-14 11:49 chenkeke $
 */

namespace App\Repositories;


use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends AbstractRepository
{

    /**
     * Get a new query builder for the posts table.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return User::query();
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
        $query = user::where('id', $id);

        return $this->scopeVisibleTo($query, $actor, $ability)->firstOrFail();
    }

}