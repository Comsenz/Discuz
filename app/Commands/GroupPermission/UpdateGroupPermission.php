<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateGroupPermission.php 28830 2019-10-23 11:09 chenkeke $
 */

namespace App\Commands\GroupPermission;


use App\Events\GroupPermission\Saving;
use App\Models\GroupPermission;
use App\Repositories\GroupRepository;
use App\Validators\GroupPermissionValidator;
use App\Exceptions\UpdateGroupPermissionException;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Collection;

class UpdateGroupPermission
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
     * 执行操作的用户组.
     *
     * @var Group
     */
    public $groupId;

    /**
     * 创建的数据.
     *
     * @var Collection
     */
    public $data;

    /**
     * 初始化命令参数
     *
     * @param $groupId
     * @param User $actor 执行操作的用户.
     * @param array $data 创建站点的数据.
     */
    public function __construct($groupId, $actor, array $data)
    {
        $this->actor = $actor;
        $this->groupId = $groupId;
        $this->data = $data;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @param GroupRepository $repository
     * @param GroupPermissionValidator $validator
     * @return GroupPermission
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(
        BusDispatcher $bus,
        EventDispatcher $events,
        GroupRepository $repository,
        GroupPermissionValidator $validator
    ) {
        $this->events = $events;

        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'groupPermission.updateGroupPermission');

        $group = $repository->findOrFail($this->groupId, $this->actor);

        // 初始数据
        $groupPermissions = [];
        foreach($this->data['permissions'] as $permission) {
            $groupPermission = ['group_id' => $group->id, 'permission' => $permission];
            // 验证参数
            $validator->valid($groupPermission);
            $groupPermissions[] = $groupPermission;
        }

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($groupPermissions, $this->actor, $this->data)
        );

        // 删除原来的用户组权限
        if (GroupPermission::where('group_id', $group->id)->delete() === false){
            throw new UpdateGroupPermissionException();
        }

        // 保存
        if (GroupPermission::insert($groupPermissions) === false){
            throw new UpdateGroupPermissionException();
        }

        // 返回数据对象
        return GroupPermission::where('group_id', $group->id)->get();
    }
}