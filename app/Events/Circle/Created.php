<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Circle;

use App\Models\Circle;

class Created
{
    /**
     * @var Circle
     */
    public $circle;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Circle $circle
     * @param User   $actor
     */
    public function __construct(Circle $circle, $actor = null)
    {
        $this->circle = $circle;
        $this->actor = $actor;
    }
}
