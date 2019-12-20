<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\UserProfile;

use App\Models\UserProfile;

class Created
{
    /**
     * @var User
     */
    public $userProfile;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param User $user
     * @param User   $actor
     */
    public function __construct(UserProfile $userProfile, $actor = null)
    {
        $this->userProfile = $userProfile;
        $this->actor = $actor;
    }
}
