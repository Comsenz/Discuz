<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Saving.php 28830 2019-10-12 15:54 chenkeke $
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