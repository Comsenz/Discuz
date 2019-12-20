<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Users;

use Exception;
use App\Models\UserIdent;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class GetMessage
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
     * @return UserIdent
     * @throws Exception
     */
    public function handle(Dispatcher $events)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        //$this->assertCan($this->actor, 'classify.deleteClassify');

        try {
            $where=[
                'mobile'=>$this->data['mobile'],
                'code'=>$this->data['code'],
                'type'=>$this->data['type'],
            ];

            $userIdent =  UserIdent::where($where)->firstOrFail();
        } catch (Exception $e) {
            throw $e;
        }

        // 返回数据对象
        return $userIdent;
    }
}
