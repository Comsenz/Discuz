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
use Discuz\Auth\Exception\LoginFailedException;
use Discuz\Auth\Exception\LoginFailuresTimesToplimitException;
use Discuz\Foundation\Application;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class CheckLogin
{
    protected $userLoginFailLog;

    protected $app;

    const FAIL_NUM = 5;

    const LIMIT_TIME = 15;

    public function __construct(UserLoginFailLogRepository $userLoginFailLog, Application $app)
    {
        $this->userLoginFailLog = $userLoginFailLog;
        $this->app = $app;
    }

    /**
     * @param Logining $event
     * @throws LoginFailuresTimesToplimitException
     * @throws LoginFailedException
     */
    public function handle(Logining $event)
    {
        $request = $this->app->make(ServerRequestInterface::class);
        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR');

        $userLoginFailCount = $this->userLoginFailLog->getCount($ip, $event->user->username);
        $maxTime = $this->userLoginFailLog->getLastFailTime($ip, $event->user->username);

        //set current count
        ++$userLoginFailCount;

        $expire = Carbon::parse($maxTime)->addMinutes(self::LIMIT_TIME);
        if ($userLoginFailCount > self::FAIL_NUM && ($expire > Carbon::now())) {
            throw new LoginFailuresTimesToplimitException;
        } elseif ($userLoginFailCount > self::FAIL_NUM && ($expire < Carbon::now())) {
            //reset fail count
            $userLoginFailCount = 1;
            UserLoginFailLog::reSetFailCountByIp($ip);
        }

        //password not match
        if ($event->password !== '' && !$event->user->checkPassword($event->password)) {
            if ($userLoginFailCount == 1) {
                //first time set fail log
                UserLoginFailLog::writeLog($ip, $event->user->id, $event->user->username);
            } else {
                //fail count +1
                UserLoginFailLog::setFailCountByIp($ip, $event->user->id, $event->user->username);

                if ($userLoginFailCount == self::FAIL_NUM) {
                    throw new LoginFailuresTimesToplimitException;
                }
            }

            throw new LoginFailedException(self::FAIL_NUM-$userLoginFailCount, 403);
        }
    }
}
