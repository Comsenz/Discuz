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

    const FAIL_NUM = 4;
    const LIMIT_TIME = 15;

    public function __construct(UserLoginFailLogRepository $userLoginFailLog,Application $app)
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

        $userLoginFailCount = $this->userLoginFailLog->getDataByIp($ip);
        $maxTime = $this->userLoginFailLog->getLastFailTime($ip);

        //password not match
        if ($event->password && ! $event->user->checkPassword($event->password)) {
            if ($userLoginFailCount) {
                //check fail count & login time limit
                $expire = Carbon::parse($maxTime)->addMinutes(self::LIMIT_TIME);
                if ($userLoginFailCount > self::FAIL_NUM && ($expire > Carbon::now())) {
                    throw new LoginFailuresTimesToplimitException;
                } elseif ($userLoginFailCount > self::FAIL_NUM && ($expire < Carbon::now())) {
                    //reset fail count
                    UserLoginFailLog::reSetFailCountByIp($ip);
                } else {
                    //add fail count
                    UserLoginFailLog::setFailCountByIp($ip);
                }
            } else {
                UserLoginFailLog::writeLog($ip, $event->user->id, $event->user->username);
            }
            throw new LoginFailedException(++$userLoginFailCount,403);
        }
    }
}
