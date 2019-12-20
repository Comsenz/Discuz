<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
