<?php
declare(strict_types=1);


namespace App\Commands\Users;


use App\Repositories\UserRepository;
use Exception;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class SendOldMessage
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
    /**
     * 创建用户的ip
     *
     * @var qcloud
     */
    public $qcloud;
    /**
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建用户的数据.
     */



    public function __construct($actor, array $data,$qcloud)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->qcloud = $qcloud;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @return UserIdent
     * @throws Exception
     */
    public function handle(Dispatcher $events,BusDispatcher $bus)
    {
        $this->events = $events;
        $this->bus = $bus;
        $where=[
            'id'=>$this->actor,
            'mobile'=> $this->data['mobile']
        ];
        User::where($where)->firstOrFail();

        $data = $this->bus->dispatch(
            new SendMessage($this->actor, $this->data, $this->qcloud)
        );


        // 返回数据对象
        return $data;
    }
}