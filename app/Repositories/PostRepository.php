<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: PostRepository.php xxx 2019-10-21 17:12:00 LiuDongdong $
 */

namespace App\Repositories;

use App\Models\Post;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class PostRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the posts table.
     *
     * @return Builder
     */
    public function query()
    {
        return Post::query();
    }

    /**
     * Find a post by ID, optionally making sure it is visible to a certain
     * user, or throw an exception.
     *
     * @param int $id
     * @param User|null $actor
     * @return Post
     */
    public function findOrFail($id, User $actor = null)
    {
        $query = $this->query()->where('id', $id);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
