<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Attachment;

use App\Models\Attachment;
use App\Models\User;

class Created
{
    /**
     * @var Attachment
     */
    public $attachment;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Attachment $attachment
     * @param User $actor
     */
    public function __construct(Attachment $attachment, User $actor = null)
    {
        $this->attachment = $attachment;
        $this->actor = $actor;
    }
}
