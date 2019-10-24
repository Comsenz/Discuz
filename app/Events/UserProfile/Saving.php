<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Saving.php 28830 2019-09-26 17:51 chenkeke $
 */

namespace App\Events\UserProfile;

use App\Models\UserProfile;

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
     * @param Circle $circle
     * @param User   $actor
     * @param array  $data
     */
    public function __construct(UserProfile $userProfile, $actor = null, array $data = [])
    {
        $this->userprofile = $userProfile;
        $this->actor = $actor;
        $this->data = $data;
    }
}