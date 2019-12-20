<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\Logining;
use App\Models\UserLoginFailLog;
use App\Repositories\UserLoginFailLogRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Discuz\Auth\Exception\LoginFailuresTimesToplimitException;
use Discuz\Auth\Exception\PermissionDeniedException;

class CheckLogin
{
    protected $userLoginFailLog;

    protected $cache;

    const LIMIT_TIME = 15;

    const CACHE_NAME = 'user_login_fail_limit_';

    public function __construct(UserLoginFailLogRepository $userLoginFailLog, CacheRepository $cache)
    {
        $this->userLoginFailLog = $userLoginFailLog;
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
        if ($this->cache->get(self::CACHE_NAME.$_SERVER['REMOTE_ADDR'])) {
            throw new LoginFailuresTimesToplimitException;
        }

        $userLoginFailCount = $this->userLoginFailLog->getDataByIp($_SERVER['REMOTE_ADDR']);
        $maxTime = $this->userLoginFailLog->getLastFailTime($_SERVER['REMOTE_ADDR']);

        //password not match
        if ($event->password && ! $event->user->checkPassword($event->password)) {
            if ($userLoginFailCount) {
                //check fail count & login time limit
                $expire = Carbon::parse($maxTime)->addMinutes(self::LIMIT_TIME);
                if ($userLoginFailCount > 4 && ($expire > Carbon::now())) {
                    $this->cache->put(self::CACHE_NAME.$_SERVER['REMOTE_ADDR'], 1, $expire);
                    throw new LoginFailuresTimesToplimitException;
                } elseif ($userLoginFailCount > 4 && ($expire < Carbon::now())) {
                    //reset fail count
                    UserLoginFailLog::reSetFailCountByIp($_SERVER['REMOTE_ADDR']);
                } else {
                    //add fail count
                    UserLoginFailLog::setFailCountByIp($_SERVER['REMOTE_ADDR']);
                }
            } else {
                UserLoginFailLog::writeLog($_SERVER['REMOTE_ADDR'], $event->user->id, $event->user->username);
            }
            throw new PermissionDeniedException;
        }
    }
}
