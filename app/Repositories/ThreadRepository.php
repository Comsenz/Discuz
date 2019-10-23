<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadRepository.php xxx 2019-10-21 17:12:00 LiuDongdong $
 */

namespace App\Repositories;

use App\Models\Thread;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ThreadRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the discussions table.
     *
     * @return Builder
     */
    public static function query()
    {
        return Thread::query();
    }

    /**
     * Find a thread by ID, optionally making sure it is visible to a
     * certain user, or throw an exception.
     *
     * @param int $id
     * @param User $user
     * @return Thread|Builder|Model
     */
    public function findOrFail($id, User $user = null)
    {
        $query = Thread::where('id', $id);

        return $this->scopeVisibleTo($query, $user)->firstOrFail();
    }
}
