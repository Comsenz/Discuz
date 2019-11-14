<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Registered.php xxx 2019-11-12 11:21:00 LiuDongdong $
 */

namespace App\Events\Users;

use App\Models\User;

class Registered
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var array
     */
    public $data;

    /**
     * @param User $user
     * @param User $actor
     * @param array $data
     */
    public function __construct(User $user, User $actor = null, array $data = [])
    {
        $this->user = $user;
        $this->actor = $actor;
        $this->data = $data;
    }
}
