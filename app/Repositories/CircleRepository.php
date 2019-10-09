<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CircleRepository.php 28830 2019-09-25 11:45 chenkeke $
 */

namespace App\Repositories;

use App\Models\Circle;

class CircleRepository
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

}