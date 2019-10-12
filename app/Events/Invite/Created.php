<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Created.php 28830 2019-10-12 15:37 chenkeke $
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