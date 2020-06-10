<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\ThreadVideo;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ThreadVideoRepository
 * @package App\Repositories
 *
 */
class ThreadVideoRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the user login log table.
     *
     * @return Model|Builder
     */
    public function query()
    {
        return ThreadVideo::query();
    }

    public function findOrFailByFileId($file_id)
    {
        return $this->query()
            ->where('file_id', $file_id)
            ->firstOrFail();
    }

    public function findOrFailByThreadId($file_id)
    {
        return $this->query()
            ->where('thread_id', $file_id)
            ->where('type', ThreadVideo::TYPE_OF_VIDEO)
            ->firstOrFail();
    }
}
