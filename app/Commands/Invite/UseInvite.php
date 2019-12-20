<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Invite;

use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;

class UseInvite
{
    use EventsDispatchTrait;

    /**
     * 执行操作的id.
     *
     * @var int
     */
    public $inviteId;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 创建站点的数据.
     *
     * @var array
     */
    public $data;

    /**
     * 执行命令
     */
    public function handle()
    {
    }
}
