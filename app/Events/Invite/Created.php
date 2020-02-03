<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Invite;

use App\Models\Invite;

class Created
{
    /**
     * @var Invite
     */
    public $invite;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Invite $invite
     * @param User   $actor
     */
    public function __construct(Invite $invite, $actor = null)
    {
        $this->invite = $invite;
        $this->actor = $actor;
    }
}
