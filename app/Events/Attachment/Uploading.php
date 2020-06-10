<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Attachment;

use App\Models\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Uploading
{
    /**
     * @var User
     */
    public $actor;

    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @param User $actor
     * @param UploadedFile $file
     */
    public function __construct(User $actor, UploadedFile $file)
    {
        $this->actor = $actor;
        $this->file = $file;
    }
}
