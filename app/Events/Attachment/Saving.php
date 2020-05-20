<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Attachment;

use App\Commands\Attachment\AttachmentUploader;
use App\Models\Attachment;
use App\Models\User;

class Saving
{
    /**
     * @var Attachment
     */
    public $attachment;

    /**
     * @var AttachmentUploader
     */
    public $uploader;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Attachment $attachment
     * @param AttachmentUploader $uploader
     * @param User $actor
     */
    public function __construct(Attachment $attachment, AttachmentUploader $uploader, User $actor = null)
    {
        $this->attachment = $attachment;
        $this->uploader = $uploader;
        $this->actor = $actor;
    }
}
