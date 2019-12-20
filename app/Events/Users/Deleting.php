<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Deleting.php 28830 2019-10-14 13:54 chenkeke $
 */

namespace App\Events\Users;


use App\Models\User;

class Deleting
{
    /**
     * @var user
     */
    public $user;

    /**
     * @var User
     */
    public $actor;

    /**
     * 管理员输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param Classify $classify
     * @param User   $actor
     * @param array  $data
     */
    public function __construct(User $user, $actor = null, array $data = [])
    {
        $this->user = $user;
        $this->actor = $actor;
        $this->data = $data;
    }
}