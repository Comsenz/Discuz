<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: UserLoginLog.php  2019-12-18 11:14:00 Xinghailong $
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $username
 * @property string $type
 * @property Carbon $created_at
 * @package App\Models
 */
class UserLoginLog extends Model
{
    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $table = 'user_login_log';

    /**
     * {@inheritdoc}
     */
    protected $dates = ['created_at'];


    /**
     * write user login log.
     *
     * @param $user_id
     * @param $username
     * @param $type
     */
    public static function writeLog($user_id,$username,$type)
    {
        $log = new static;

        $log->user_id = $user_id;
        $log->username = $username;
        $log->type = $type;
        $log->created_at = Carbon::now();

        $log->save();
    }

}
