<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleExtend.phpnd.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\Classify;

use Discuz\Auth\AssertPermissionTrait;
use Exception;
use App\Models\Classify;
use App\Events\Classify\Saving;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class CreateClassify
{
    use EventsDispatchTrait;
    use AssertPermissionTrait;
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
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建圈子的数据.
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
     * @return Circle
     * @throws Exception
     */
    public function handle(BusDispatcher $bus, EventDispatcher $events)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'classify.createClassify');

        // 初始圈子数据
        $classify = Classify::creation(
            $this->data['name'],
            $this->data['description'],
            $this->data['icon'],
            $this->data['sort'],
            $this->data['property'],
            $this->ipAddress
        );

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($classify, $this->actor, $this->data)
        );

        // 保存圈子
        $classify->save();

        // 调用钩子事件
        $this->dispatchEventsFor($classify);

        // 返回数据对象
        return $classify;
    }
}