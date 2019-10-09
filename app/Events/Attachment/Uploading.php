<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Uploading.php 28830 2019-09-30 16:36 chenkeke $
 */

namespace App\Events\Attachment;


class Uploading
{

    /**
     * @var User
     */
    public $actor;

    /**
     * @var User
     */
    public $file;

    /**
     * @var User
     */
    public $type;

    /**
     * @param Circle $circle
     * @param User   $actor
     */
    public function __construct($actor, $file, $type)
    {
        $this->actor = $actor;
        $this->file = $file;
        $this->type = $type;
    }
}