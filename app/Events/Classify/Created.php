<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Createdd.php 28830 2019-09-26 17:15 chenkeke $
 */

namespace App\Events\Classify;

use App\Models\Classify;

class Created
{
    /**
     * @var Classify
     */
    public $classify;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Classify $classify
     * @param User   $actor
     */
    public function __construct(Classify $classify, $actor = null)
    {
        $this->classify = $classify;
        $this->actor = $actor;
    }
}