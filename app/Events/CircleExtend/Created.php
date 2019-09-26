<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      1: Createdd.php 28830 2019-09-26 17:15 chenkeke $
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