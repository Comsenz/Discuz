<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: WriteLoginLog.php  2019-12-18 15:49 Xinghailong $
 */
namespace App\Listeners\User;

use App\Events\Users\Logind;
use App\Models\UserLoginLog;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class WriteLoginLog
{

    protected $cache;
    const CACHE_NAME = 'user_login_fail_';

    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
    }

    public function handle(Logind $event)
    {
        $this->cache->delete(self::CACHE_NAME.$event->user->id);
        UserLoginLog::writeLog($event->user->id,$event->user->username,0);
    }
}
