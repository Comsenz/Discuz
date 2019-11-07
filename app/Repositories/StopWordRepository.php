<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: StopWordRepository.php xxx 2019-11-06 16:50:00 LiuDongdong $
 */

namespace App\Repositories;

use App\Models\StopWord;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class StopWordRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the stop_words table.
     *
     * @return Builder
     */
    public function query()
    {
        return StopWord::query();
    }

    /**
     * Find a stop word by ID, optionally making sure it is visible to a certain
     * user, or throw an exception.
     *
     * @param int $id
     * @param User $actor
     * @return StopWord
     */
    public function findOrFail($id, User $actor = null)
    {
        $query = StopWord::where('id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
