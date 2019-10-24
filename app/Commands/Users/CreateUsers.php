<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleExtend.phpnd.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\Users;


use App\Events\Users\Saving;
use Exception;
use App\Models\User;
use App\Commands\UserProfile\CreateUserProfile;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use App\Validators\UserValidator;
class CreateUsers
{
    use EventsDispatchTrait;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 创建用户的数据.
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
     *  验证请求
     *
     * @var Validator
     */
    protected $userValidator;
    /**
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建用户的数据.
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
    public function handle(BusDispatcher $bus, EventDispatcher $events, UserValidator $userValidator)
    {
        $this->events = $events;
        $this->userValidator =$userValidator;
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'circle.createCircle');

        //验证数据
        $this->userValidator->valid(['username' => $this->data['username'], 'password' => $this->data['password']]);

        // 初始圈子数据
        $user = User::creation(
            $this->data['username'],
            User::setUserPasswordAttr($this->data['password']),
            '',
            $this->data['adminid'],
            $this->ipAddress
        );

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($user)
        );

        // 保存用户
        $user->save();

        // 分发创建圈子扩展信息的任务
        try {
            $bus->dispatch(
                new CreateUserProfile($user->id, $this->actor, $this->data, $this->ipAddress)
            );
        } catch (Exception $e) {
            $user->delete();
            throw $e;
        }
        // 调用钩子事件
        $this->dispatchEventsFor($user);
        // 返回数据对象
        return $user;
    }
}