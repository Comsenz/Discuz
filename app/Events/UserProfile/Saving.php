<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\UserProfile;

use App\Models\User;

class Saving
{
    /**
     * @var Users
     */
    public $userprofile;

    /**
     * @var User
     */
    public $actor;

    /**
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param user $userProfile
     * @param User   $actor
     * @param array  $data
     */
    public function __construct(User $userProfile, $actor = null, array $data = [])
    {
        $this->userprofile = $userProfile;
        $this->actor = $actor;
        $this->data = $data;
    }
}
