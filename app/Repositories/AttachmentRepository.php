<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Attachment;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class AttachmentRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the attachments table.
     *
     * @return Builder
     */
    public function query()
    {
        return Attachment::query();
    }

    /**
     * Find a attachment by uuid, optionally making sure it is visible to a
     * certain user, or throw an exception.
     *
     * @param $uuid
     * @param User|null $actor
     * @return Attachment
     */
    public function findOrFail($uuid, User $actor = null)
    {
        $query = Attachment::where('uuid', $uuid);

        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
