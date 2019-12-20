<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Saving.php 28830 2019-09-26 17:51 chenkeke $
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