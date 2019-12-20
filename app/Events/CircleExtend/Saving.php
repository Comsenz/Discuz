<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\CircleExtend;

use App\Models\CircleExtend;

class Saving
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
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param CircleExtend $circleExtend
     * @param User          $actor
     * @param array         $data
     */
    public function __construct(CircleExtend $circleExtend, $actor = null, array $data = [])
    {
        $this->circleExtend = $circleExtend;
        $this->actor = $actor;
        $this->data = $data;
    }
}
