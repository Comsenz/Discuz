<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      1: Saving.php 28830 2019-09-26 17:51 chenkeke $
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