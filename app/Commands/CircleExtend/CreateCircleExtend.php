<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      1: CreateCircleExtend.phpnd.php 28830 2019-09-26 10:09 chenkeke $
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
     * 圈子的ID.
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
     * 创建圈子的数据.
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
     * @param string $circleId  圈子的ID.
     * @param User   $actor     执行操作的用户.
     * @param array  $data      创建圈子的数据.
     * @param string $ipAddress 请求来源的IP地址.
     */
    public function __construct(string $circleId, $actor, array $data, string $ipAddress)
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
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'createCircleExtend');

        // 初始圈子扩展数据
        $circleExtend = CircleExtend::create(
            $this->circleId,
            $this->data['type'],
            $this->data['price'],
            $this->data['share_rule'],
            $this->ipAddress
        );

        // 触发钩子事件
        $events->dispatch(
            new Saving($circleExtend, $this->actor, $this->data)
        );

        // 保存圈子扩展信息
        $circleExtend->save();

        // 调用钩子事件
        $this->dispatchEventsFor($circleExtend);

        // 返回数据对象
        return $circleExtend;
    }
}