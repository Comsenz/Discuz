<?php
declare(strict_types=1);


namespace App\Commands\Users;


use App\SmsMessages\SendCodeMessage;
use Exception;
use App\Models\UserIdent;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class SendMessage
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
    public function handle(Dispatcher $events)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        //$this->assertCan($this->actor, 'classify.deleteClassify');

        //生成验证码
        $code=rand(100000,999999);
        $userIdent = UserIdent::creation(
            $this->data['type'],
            $code,
            $this->data['mobile']
        );;
        $userIdent->save();
        try {
            $this->qcloud->service('sms')->send($this->data['mobile'], new SendCodeMessage(['code' => $code, 'expire' => '5']));
        } catch (Exception $e) {
            $userIdent->delete();
            throw $e;
        }

        // 返回数据对象
        return $userIdent;
    }
}