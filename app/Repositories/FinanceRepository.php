<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Finance;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class FinanceRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the attachments table.
     *
     * @return Builder
     */
    public function query()
    {
        return Finance::query();
    }

}
