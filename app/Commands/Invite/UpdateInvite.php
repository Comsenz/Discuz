<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateInvite.php 28830 2019-10-12 17:16 chenkeke $
 */

namespace App\Commands\Invite;


use App\Events\Invite\Saving;
use App\Models\User;
use App\Repositories\InviteRepository;
use App\Validators\InviteValidator;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class UpdateInvite
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
     * 请求来源的IP地址.
     *
     * @var string
     */
    public $ipAddress;

    /**
     * 初始化命令参数
     *
     * @param int $inviteId 执行操作的id
     * @param User $actor 执行操作的用户.
     * @param array $data 创建站点的数据.
     * @param string $ipAddress 请求来源的IP地址.
     */
    public function __construct($inviteId, User $actor, array $data, string $ipAddress)
    {
        $this->inviteId = $inviteId;
        $this->actor = $actor;
        $this->data = $data;
        $this->ipAddress = $ipAddress;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @param InviteRepository $repository
     * @param InviteValidator $validator
     * @return \Flarum\Group\Group
     */
    public function handle(
        BusDispatcher $bus,
        EventDispatcher $events,
        InviteRepository $repository,
        InviteValidator $validator
    ) {
        $this->events = $events;

        $invite = $repository->findOrFail($this->inviteId, $this->actor, 'findEdit');

        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'updateInvite', $invite);

        if (isset($this->data['status'])) {
            $invite->status = $this->data['status'];
        }

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($invite, $this->actor, $this->data)
        );

        // 验证参数
        $validator->assertValid($invite->getDirty());

        // 保存站点
        $invite->save();

        // 调用钩子事件
        $this->dispatchEventsFor($invite);

        // 返回数据对象
        return $invite;
    }
}