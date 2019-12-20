<?php


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
use Illuminate\Support\Arr;

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
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct($actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
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
        // $this->assertCan($this->actor, 'createInvite');

        // 生成邀请码
        $code = Str::random(32);
        $dateline = Carbon::now()->timestamp;
        //7天有效期
        $endtime  = Carbon::now()->addDay(7)->timestamp;

        // 初始数据
        $invite = Invite::creation(
            Arr::get($this->data, 'attributes.group_id'),
            2,
            $code,
            $dateline,
            $endtime,
            $this->actor->id ?: 0
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