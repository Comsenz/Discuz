<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleExtend.phpnd.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\UserProfile;

use Exception;
use App\Models\UserProfile;
use App\Events\UserProfile\Saving;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class CreateUserProfile
{
    use EventsDispatchTrait;

    /**
     * 圈子的ID.
     *
     * @var string
     */
    public $userId;

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
     * @param int    $circleId  圈子的ID.
     * @param User   $actor     执行操作的用户.
     * @param array  $data      创建圈子的数据.
     * @param string $ipAddress 请求来源的IP地址.
     */
    public function __construct(int $userId, $actor, array $data, string $ipAddress)
    {
        $this->userId = $userId;
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

        // 初始圈子扩展数据
        $userProfile = UserProfile::creation(
            $this->userId,
           '',
            '',
            $this->ipAddress
        );

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($userProfile, $this->actor, $this->data)
        );

        // 保存圈子扩展信息
        $userProfile->save();

        // 调用钩子事件
        $this->dispatchEventsFor($userProfile);

        // 返回数据对象
        return $userProfile;
    }
}