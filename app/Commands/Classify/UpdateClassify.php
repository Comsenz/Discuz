<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateClassifyfy.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\Classify;

use App\Models\User;
use App\Repositories\ClassifyRepository;
use App\Validators\ClassifyValidator;
use Discuz\Auth\AssertPermissionTrait;
use Exception;
use App\Models\Classify;
use App\Events\Classify\Saving;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class UpdateClassify
{
    use EventsDispatchTrait;
    use AssertPermissionTrait;
    /**
     * 执行操作的id.
     *
     * @var int
     */
    public $classifyId;

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
     * @param int $classifyId 执行操作的id
     * @param User $actor 执行操作的用户.
     * @param array $data 修改的数据.
     * @param string $ipAddress 请求来源的IP地址.
     */
    public function __construct($classifyId, User $actor, array $data, string $ipAddress)
    {
        $this->classifyId = $classifyId;
        $this->actor = $actor;
        $this->data = $data;
        $this->ipAddress = $ipAddress;
    }

    /**
     * 执行命令
     *
     * @param EventDispatcher $events
     * @param ClassifyRepository $repository
     * @param ClassifyValidator $validator
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(
        EventDispatcher $events,
        ClassifyRepository $repository,
        ClassifyValidator $validator
    ) {
        $this->events = $events;

        $classify = $repository->findOrFail($this->classifyId, $this->actor);

        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'classify.updateClassify', $classify);

        if (isset($this->data['name'])) {
            $classify->name = $this->data['name'];
        }

        if (isset($this->data['description'])) {
            $classify->description = $this->data['description'];
        }

        if (isset($this->data['icon'])) {
            $classify->icon = $this->data['icon'];
        }
        if (isset($this->data['sort'])) {
            $classify->sort = $this->data['sort'];
        }
        if (isset($this->data['property'])) {
            $classify->property = $this->data['property'];
        }

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($classify, $this->actor, $this->data)
        );

        // 验证参数
        $validator->valid($classify->getDirty());

        // 保存
        $classify->save();

        // 调用钩子事件
        $this->dispatchEventsFor($classify);

        // 返回数据对象
        return $classify;
    }
}