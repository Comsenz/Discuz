<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CheckLogin.php  2019-12-18 15:49 Xinghailong $
 */
namespace App\Listeners\User;

use App\Events\Users\Logining;
use App\Models\UserLoginLog;
use App\Repositories\UserLoginLogRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Discuz\Auth\Exception\LoginFailuresTimesToplimitException;
use Discuz\Auth\Exception\PermissionDeniedException;

class CheckLogin
{

    protected $userLoginLog;
    protected $cache;
    const LIMIT_TIME = 15;
    const CACHE_NAME = 'user_login_fail_';


    public function __construct(UserLoginLogRepository $userLoginLog, CacheRepository $cache)
    {
        $this->userLoginLog = $userLoginLog;
        $this->cache = $cache;
    }

    /**
     * @param Logining $event
     * @throws LoginFailuresTimesToplimitException
     * @throws PermissionDeniedException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function handle(Logining $event)
    {
        if ($this->cache->get(self::CACHE_NAME.$event->user->id)) {
            throw new LoginFailuresTimesToplimitException;
        }
        $userLoginFailureNum = $this->userLoginLog->getUserLoginLogFailuresCount($event->user->id);
        $time = $this->userLoginLog->getLastLoginTime($event->user->id);

        $expire = Carbon::parse($time)->addMinute(self::LIMIT_TIME);
        if ($userLoginFailureNum > 4 && $expire > Carbon::now()){
            $this->cache->put(self::CACHE_NAME.$event->user->id,1,$expire);
            throw new LoginFailuresTimesToplimitException;
        }
        if ($event->password && ! $event->user->checkPassword($event->password) ) {
            UserLoginLog::writeLog($event->user->id,$event->user->username,1);
            throw new PermissionDeniedException;
        }
    }
}
