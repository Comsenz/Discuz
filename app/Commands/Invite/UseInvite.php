<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UseInvite.php 28830 2019-11-19 17:16 yanchen $
 */

namespace App\Commands\Invite;

use App\Events\Invite\Saving;
use App\Models\User;
use App\Repositories\InviteRepository;
use App\Validators\InviteValidator;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

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
     * 创建圈子的数据.
     *
     * @var array
     */
    public $data;

    /**
     * 执行命令
     */
    public function handle() {

    }
}