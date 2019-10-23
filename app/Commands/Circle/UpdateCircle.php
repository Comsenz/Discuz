<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateCircle.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\Circle;

use App\Models\User;
use App\Repositories\CircleRepository;
use App\Validators\CircleValidator;
use Exception;
use App\Models\Circle;
use App\Events\Circle\Saving;
use App\Commands\CircleExtend\CreateCircleExtend;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class UpdateCircle
{
    use EventsDispatchTrait;

    /**
     * 执行操作的圈子id.
     *
     * @var int
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
     * @param int $circleId 执行操作的圈子id
     * @param User $actor 执行操作的用户.
     * @param array $data 创建圈子的数据.
     * @param string $ipAddress 请求来源的IP地址.
     */
    public function __construct($circleId, User $actor, array $data, string $ipAddress)
    {
        $this->circleId = $circleId;
        $this->actor = $actor;
        $this->data = $data;
        $this->ipAddress = $ipAddress;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @param CircleRepository $repository
     * @param CircleValidator $validator
     * @return \Flarum\Group\Group
     */
    public function handle(
        BusDispatcher $bus,
        EventDispatcher $events,
        CircleRepository $repository,
        CircleValidator $validator
    ) {
        $this->events = $events;

        $circle = $repository->findOrFail($this->circleId, $this->actor);

        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'circle.updateCircle', $circle);

        if (isset($this->data['color'])) {
            $circle->color = $this->data['color'];
        }

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($circle, $this->actor, $this->data)
        );

        // 分发创建圈子扩展信息的任务
        $bus->dispatch(
            new CreateCircleExtend($circle->id, $this->actor, $this->data, $this->ipAddress)
        );

        // 验证参数
        $validator->assertValid($circle->getDirty());

        // 保存圈子
        $circle->save();

        // 调用钩子事件
        $this->dispatchEventsFor($circle);

        // 返回数据对象
        return $circle;
    }
}