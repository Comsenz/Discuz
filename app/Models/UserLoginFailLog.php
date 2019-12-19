<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: UserLoginFailLog.php  2019-12-18 11:14:00 Xinghailong $
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $ip
 * @property int $user_id
 * @property string $username
 * @property int $count
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
 */
class UserLoginFailLog extends Model
{

    /**
     * {@inheritdoc}
     */
    protected $table = 'user_login_fail_log';


    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    /**
     * write user login fail log.
     *
     * @param $ip
     * @param $user_id
     * @param $username
     */
    public static function writeLog($ip, $user_id, $username)
    {
        $log = new static;

        $log->ip = $ip;
        $log->user_id = $user_id;
        $log->username = $username;
        $log->count = 1;
        $log->created_at = Carbon::now();

        $log->save();
    }

    /**
     * add fail count
     *
     * @param $log
     * @param $ip
     */
    public static function setFailCountByIp($ip){
        $log = self::getDataByIp($ip);
        $log->increment('count');
    }

    /**
     * refresh fail count
     * @param $ip
     */
    public static function reSetFailCountByIp($ip){
        $log = self::getDataByIp($ip);
        $log->count = 1;
        $log->update();

    }

    /**
     * get fail data by ip
     * @param $ip
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public static function getDataByIp($ip){
        return self::query()
            ->where(['ip'=>$ip])
            ->first();
    }
}
