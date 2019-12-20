<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use App\Events\Users\Saving;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Exception;
use App\Models\User;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class LoginMessage
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

    public $bus;

    public $userValidator;

    public $ipAddress;

    /**
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建用户的数据.
     */
    public function __construct($actor, array $data, $ipAddress)
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
     * @return ResponseBag
     * @throws Exception
     */
    public function handle(Dispatcher $events, BusDispatcher $bus, UserValidator $userValidator, UserRepository $repository)
    {
        $this->events = $events;
        $this->userValidator =$userValidator;
        $this->bus = $bus;
        // 判断有没有权限执行此操作
        //$this->assertCan($this->actor, 'classify.deleteClassify');

        $data = $this->bus->dispatch(
            new GetMessage($this->actor, $this->data)
        );
        $data->delete();
        try {
            $objuser = User::where('mobile', $this->data['mobile'])->firstOrFail();
            $objuser->login_ip=$this->ipAddress;
            $objuser->save();
        } catch (Exception $e) {
            throw $e;
        }
        // 触发钩子事件
        $this->events->dispatch(
            new Saving($objuser, $this->actor, $this->data)
        );
        $this->dispatchEventsFor($objuser);
        // 返回数据对象
        return $objuser;
    }
}
