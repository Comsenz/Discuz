<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Invite;

use App\Models\Invite;

class Saving
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
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param Invite $invite
     * @param User   $actor
     * @param array  $data
     */
    public function __construct(Invite $invite, $actor = null, array $data = [])
    {
        $this->invite = $invite;
        $this->actor = $actor;
        $this->data = $data;
    }
}
