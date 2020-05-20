<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Attachment;

use App\Commands\Attachment\AttachmentUploader;
use App\Models\User;

class Uploaded
{
    /**
     * @var User
     */
    public $actor;

    /**
     * @var AttachmentUploader
     */
    public $uploader;

    /**
     * @var array
     */
    public $data;

    /**
     * @param User $actor
     * @param AttachmentUploader $uploader
     */
    public function __construct(User $actor, AttachmentUploader $uploader)
    {
        $this->actor = $actor;
        $this->uploader = $uploader;
    }
}
