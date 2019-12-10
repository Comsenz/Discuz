<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleExtend.phpnd.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\CircleExtend;

use Exception;
use App\Models\CircleExtend;
use App\Events\CircleExtend\Saving;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class CreateCircleExtend
{
    use EventsDispatchTrait;

    /**
     * 站点的ID.
     *
     * @var string
     */
    public $circleId;

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
     * @param int    $circleId  站点的ID.
     * @param User   $actor     执行操作的用户.
     * @param array  $data      创建站点的数据.
     * @param string $ipAddress 请求来源的IP地址.
     */
    public function __construct(int $circleId, $actor, array $data, string $ipAddress)
    {
        $this->circleId = $circleId;
        $this->actor = $actor;
        $this->data = $data;
        $this->ipAddress = $ipAddress;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher   $bus
     * @param EventDispatcher $events
     * @return CircleExtend
     * @throws Exception
     */
    public function handle(BusDispatcher $bus, EventDispatcher $events)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'circleExtend.createCircleExtend');

        // 初始站点扩展数据
        $circleExtend = CircleExtend::creation(
            $this->circleId,
            $this->data['type'],
            $this->data['price'],
            $this->data['indate_type'],
            $this->data['indate_time'],
            $this->data['join_circle_ratio_master'],
            $this->data['read_thread_ratio_master'],
            $this->data['read_thread_ratio_admin'],
            $this->data['give_thread_ratio_master'],
            $this->data['give_thread_ratio_admin'],
            $this->ipAddress
        );

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($circleExtend, $this->actor, $this->data)
        );

        // 保存站点扩展信息
        $circleExtend->save();

        // 调用钩子事件
        $this->dispatchEventsFor($circleExtend);

        // 返回数据对象
        return $circleExtend;
    }
}