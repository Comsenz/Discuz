<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Uploading.php 28830 2019-09-30 16:36 chenkeke $
 */

namespace App\Events\Attachment;

use App\Models\User;
use Psr\Http\Message\UploadedFileInterface;

class Uploading
{
    /**
     * @var User
     */
    public $actor;

    /**
     * @var UploadedFileInterface
     */
    public $file;

    /**
     * @param User $actor
     * @param UploadedFileInterface $file
     */
    public function __construct(User $actor, UploadedFileInterface $file)
    {
        $this->actor = $actor;
        $this->file = $file;
    }
}
