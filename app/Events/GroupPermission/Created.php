<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Created.php 28830 2019-10-23 11:15 chenkeke $
 */

namespace App\Events\GroupPermission;


use App\Models\GroupPermission;

class Created
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
     * @param GroupPermission $groupPermission
     * @param User   $actor
     */
    public function __construct(GroupPermission $groupPermission, $actor = null)
    {
        $this->groupPermission = $groupPermission;
        $this->actor = $actor;
    }
}