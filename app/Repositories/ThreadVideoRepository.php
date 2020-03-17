<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\ThreadVideo;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class ThreadVideoRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the user login log table.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return ThreadVideo::query();
    }

    public function findOrFailByFileId($file_id)
    {
        $query = $this->query();
        $query->where('file_id', $file_id);

        return $query->firstOrFail();
    }

    public function findOrFailByThreadId($file_id)
    {
        $query = $this->query();
        $query->where('thread_id', $file_id);

        return $query->firstOrFail();
    }
}
