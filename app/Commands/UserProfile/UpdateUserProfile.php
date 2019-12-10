<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleExtend.phpnd.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\UserProfile;

use App\Models\User;
use App\Models\UserWechat;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Exception;
use App\Models\UserProfile;
use App\Commands\Users\MessageBinding;
use App\Commands\Users\UpdatePwd;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Database\ConnectionInterface;

class UpdateUserProfile
{
    use EventsDispatchTrait;

    /**
     * 站点的ID.
     *
     * @var string
     */
    public $userid;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 创建站点的数据.
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
     * 初始化命令参数
     *
     * @param int    $circleId  站点的ID.
     * @param User   $actor     执行操作的用户.
     * @param array  $data      创建站点的数据.
     * @param string $ipAddress 请求来源的IP地址.
     */
    public function __construct($userId, $actor, array $data, string $ipAddress)
    {
        $this->userid = $userId;
        $this->actor = $actor;
        $this->data = $data;
        $this->ipAddress = $ipAddress;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher   $bus
     * @param EventDispatcher $events
     * @return CircleExtend
     * @throws Exception
     */
    public function handle(BusDispatcher $bus, EventDispatcher $events, ConnectionInterface $db, UserRepository $repository, UserValidator $validator)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'circleExtend.createCircleExtend');
        $userProfile = $repository->findOrFail($this->userid, $this->actor);
        try {
        if (isset($this->data['username'])&& $userProfile->username!=$this->data['username']) {
            $validator->valid(['username' => $this->data['username']]);
            $userProfile->username = $this->data['username'];
        }
        if (isset($this->data['password'])&& is_array($this->data['password'])) {
            $validator->valid(['password' => $this->data['password']['password']]);
            $data = $bus->dispatch(
                new UpdatePwd($this->userid, $this->actor, $this->data['password'], $userProfile->password)
            );
        }else if (isset($this->data['password'])){
            //$this->assertCan($this->actor, 'circleExtend.createCircleExtend');
            $userProfile->password = User::setUserPasswordAttr($this->data['password']);
        }

        if (isset($this->data['mobile'])&& is_array($this->data['mobile'])) {
            $data = $bus->dispatch(
                new MessageBinding($this->actor, $this->data['mobile'],$bus)
            );
        }else if (isset($this->data['mobile'])){
            //$this->assertCan($this->actor, 'circleExtend.createCircleExtend');
            $userProfile->mobile = $this->data['mobile'];
        }

        $userProfile->save();
        $profile = UserProfile::where('user_id',$this->userid)->first();
        if($profile){
            $profile_id=$profile->id;
        }else{
            // dd($data['profile']);
            $userprofile=UserProfile::create(["user_id"=>$this->userid]);
            $profile_id=$userprofile->id;
        }
        $objprofile = UserProfile::findOrFail($profile_id);
        if (isset($this->data['delete_icon'])=="true") {
            $objprofile->icon = '';
        }
        if (isset($this->data['sex'])) {
            $objprofile->sex = $this->data['sex'];
        }
        $objprofile->save();
        if (isset($this->data['delete_wx'])=="true") {
            UserWechat::where('id',$this->userid)->delete();
        }
        // 保存站点扩展信息
        $userProfile->save();
        } catch (Exception $e) {
            throw $e;
        }
        // 调用钩子事件
        $this->dispatchEventsFor($userProfile);

        // 返回数据对象
        return $userProfile;
    }
}