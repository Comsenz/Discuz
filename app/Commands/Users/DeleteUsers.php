<?php
declare(strict_types=1);


namespace App\Commands\Users;


use App\Events\Users\Deleting;
use App\Repositories\UserRepository;
use Exception;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteUsers
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
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建用户的数据.
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
     * @return User
     * @throws Exception
     */
    public function handle(Dispatcher $events,UserRepository $repository)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        //$this->assertCan($this->actor, 'classify.deleteClassify');

        foreach ($this->data['user'] as $k => $v) {

            $user=User::where('id',$v)->first();
            if($user){
                $repository->findOrFail($v,$this->actor);
                $this->events->dispatch(
                    new Deleting($user, $this->actor, ['$v'])
                );

                $user->delete();
                // 调用钩子事件
                $this->dispatchEventsFor($user);
            }

        }

        // 返回数据对象
        return $user;
    }
}