<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\CircleExtend;

use App\Models\CircleExtend;

class Created
{
    /**
     * @var CircleExtend
     */
    public $circleExtend;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param CircleExtend $circleExtend
     * @param User          $actor
     */
    public function __construct(CircleExtend $circleExtend, $actor = null)
    {
        $this->circleExtend = $circleExtend;
        $this->actor = $actor;
    }
}
