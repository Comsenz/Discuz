<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateInvite.php 28830 2019-10-12 15:52 chenkeke $
 */

namespace App\Commands\Invite;


use App\Events\Invite\Saving;
use App\Models\Invite;
use Carbon\Carbon;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Str;

class CreateInvite
{
    use EventsDispatchTrait;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 请求的数据.
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
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     * @param string $ipAddress    请求来源的IP地址.
     */
    public function __construct($actor, array $data, string $ipAddress)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->ipAddress = $ipAddress;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @return Invite
     * @throws Exception
     */
    public function handle(BusDispatcher $bus, EventDispatcher $events)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'createCircle');

        // 生成邀请码
        $code = Str::random(32);
        $dateline = Carbon::now()->timestamp;
        $endtime = Carbon::parse('2099-01-01')->timestamp;

        // 初始数据
        $invite = Invite::creation(
            $this->data['user_group_id'],
            $code,
            $dateline,
            $endtime,
            $this->actor['id']?:0
        );

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($invite, $this->actor, $this->data)
        );

        // 保存
        $invite->save();

        // 调用钩子事件
        $this->dispatchEventsFor($invite);

        // 返回数据对象
        return $invite;
    }
}