<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Created.php 28830 2019-10-08 15:26 chenkeke $
 */

namespace App\Events\Attachment;

use App\Models\Attachment;

class Created
{
    /**
     * @var Attachment
     */
    public $attach;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Attachment $attach
     * @param User       $actor
     */
    public function __construct(Attachment $attach, $actor = null)
    {
        $this->attach = $attach;
        $this->actor = $actor;
    }
}