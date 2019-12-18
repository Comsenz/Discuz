<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Attachment;

use App\Models\Attachment;
use App\Models\User;

class Deleting
{
    /**
     * The attachment that is going to be deleted.
     *
     * @var Attachment
     */
    public $attachment;

    /**
     * The user who is performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * Any user input associated with the command.
     *
     * @var array
     */
    public $data;

    /**
     * @param Attachment $attachment
     * @param User $actor
     * @param array $data
     */
    public function __construct(Attachment $attachment, User $actor, array $data = [])
    {
        $this->attachment = $attachment;
        $this->actor = $actor;
        $this->data = $data;
    }
}
