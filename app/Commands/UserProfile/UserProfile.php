<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleExtend.phpnd.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\UserProfile;


use App\Events\Userprofile\Saving;
use App\Repositories\UserRepository;
use Exception;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
class UserProfile
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
    public $id;

    /**
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建用户的数据.
     */



    public function __construct($id,$actor)
    {
        $this->actor = $actor;
        $this->id = $id;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @return user
     * @throws Exception
     */
    public function handle(BusDispatcher $bus, EventDispatcher $events,UserRepository $repository)
    {
        $this->events = $events;
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'circle.createCircle');

        //验证数据
        $userProfile= User::where('users.id',$this->id)
        ->leftjoin('user_wechats', 'user_wechats.id', '=', 'users.id')
        ->leftjoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
        ->select('users.id as id',"username","adminid","users.unionid","mobile","users.createtime as createtime","users.login_ip","nickname","user_profiles.sex","icon")
        ->first();

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($userProfile,$this->actor,[$this->id])
        );

        // 调用钩子事件
        $this->dispatchEventsFor($userProfile);
        // 返回数据对象
        return $userProfile;
    }
}