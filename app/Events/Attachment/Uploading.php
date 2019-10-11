<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Uploading.php 28830 2019-09-30 16:36 chenkeke $
 */

namespace App\Events\Attachment;


use Discuz\Contracts\Tool\UploadTool;

class Uploading
{

    /**
     * @var User
     */
    public $actor;

    /**
     * @var UploadTool
     */
    public $uploadTool;

    /**
     * @param User       $actor
     * @param UploadTool $uploadTool
     */
    public function __construct($actor, UploadTool $uploadTool)
    {
        $this->actor = $actor;
        $this->uploadTool = $uploadTool;
    }
}