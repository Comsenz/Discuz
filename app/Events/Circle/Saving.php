<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Saving.php 28830 2019-09-26 17:51 chenkeke $
 */

namespace App\Events\Circle;

use App\Models\Circle;

class Saving
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
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param Circle $circle
     * @param User   $actor
     * @param array  $data
     */
    public function __construct(Circle $circle, $actor = null, array $data = [])
    {
        $this->circle = $circle;
        $this->actor = $actor;
        $this->data = $data;
    }
}