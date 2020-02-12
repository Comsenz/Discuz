<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Dialog;
use App\Models\DialogMessage;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class DialogMessageRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the attachments table.
     *
     * @return Builder
     */
    public function query()
    {
        return DialogMessage::query();
    }

    public function findOrFail(User $actor = null)
    {
        $query = $this->query();
        return $query->firstOrFail();
    }

}
