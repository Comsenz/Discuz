<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Saving.php 28830 2019-10-23 11:21 chenkeke $
 */

namespace App\Events\GroupPermission;

class Saving
{
    /**
     * @var GroupPermission
     */
    public $groupPermission;

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
     * @param GroupPermission $groupPermission
     * @param User   $actor
     * @param array  $data
     */
    public function __construct($groupPermission, $actor = null, array $data = [])
    {
        $this->groupPermission = $groupPermission;
        $this->actor = $actor;
        $this->data = $data;
    }
}