<?php
declare(strict_types=1);


namespace App\Commands\Users;


use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Exception;
use App\Models\User;
use App\Models\UserIdent;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class MessageBinding
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

    /**
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建用户的数据.
     */



    public function __construct($actor, array $data ,BusDispatcher $bus)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->bus = $bus;

    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @return ResponseBag
     * @throws Exception
     */
    public function handle(Dispatcher $events,UserValidator $userValidator,UserRepository $repository)
    {
        $this->events = $events;
        $this->userValidator =$userValidator;
        // 判断有没有权限执行此操作
        //$this->assertCan($this->actor, 'classify.deleteClassify');
        if($this->data['type']=='new'){
            $where=[
                'id'=>$this->data['id'],
                'type'=>'update',
            ];
            $userIdent =  UserIdent::where($where)->firstOrFail();
            $userIdent->delete();
        }

        $data = $this->bus->dispatch(
            new GetMessage($this->actor, $this->data)
        );
        $data->delete();
        try {
            $objuser = $repository->findOrFail(24,$this->actor);
            if($objuser->mobile !=$this->data['mobile']){
                //$this->userValidator->valid(['mobile' => $this->data['mobile']]);
                $objuser->mobile = $this->data['mobile'];
            }
            $objuser->save();
            
        } catch (Exception $e) {
            throw $e;
        }
        $this->dispatchEventsFor($objuser);
        // 返回数据对象
        return $objuser;
    }
}