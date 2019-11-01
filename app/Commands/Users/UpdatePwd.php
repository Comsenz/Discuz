<?php
declare(strict_types=1);


namespace App\Commands\Users;


use App\Validators\UserValidator;
use Exception;
use App\Models\User;
use App\Models\UserIdent;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class UpdatePwd
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
    public $pwd;
    public $data;
    public $userid;
    public $userValidator;

    /**
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建用户的数据.
     */



    public function __construct($userid,$actor, array $data ,$pwd)
    {
        $this->actor = $actor;
        $this->userid = $userid;
        $this->data = $data;
        $this->pwd=$pwd;

    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @return ResponseBag
     * @throws Exception
     */
    public function handle(Dispatcher $events,UserValidator $userValidator)
    {
        $this->events = $events;
        $this->userValidator =$userValidator;
        // 判断有没有权限执行此操作
        //$this->assertCan($this->actor, 'classify.deleteClassify');

        try {
            $where=[
                'id'=>$this->userid,
                'password'=>User::unsetUserPasswordAttr($this->data['oldpwd'],$this->pwd)?$this->pwd:""
            ];
            $objuser = User::where($where)->firstOrFail();
            $objuser->password = User::setUserPasswordAttr($this->data['password']);
            $objuser->save();

        } catch (Exception $e) {
            throw $e;
        }
        $this->dispatchEventsFor($objuser);
        // 返回数据对象
        return $objuser;
    }
}