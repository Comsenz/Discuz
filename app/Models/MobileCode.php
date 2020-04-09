<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $mobile
 * @property string $code
 * @property string $type
 * @property string $ip
 * @property int $state
 * @property \Carbon\Carbon $expired_at
 * @property mixed user
 */
class MobileCode extends Model
{
    const USED_STATE = 1;

    protected $fillable = ['mobile', 'code', 'type', 'expired_at'];

    /**
     * @param $mobile
     * @param $exception
     * @param $type
     * @param $ip
     * @return MobileCode
     * @throws \Exception
     */
    public static function make($mobile, $exception, $type, $ip)
    {
        $mobileCode = new static();
        $mobileCode->mobile = $mobile;
        $mobileCode->code = static::genCode();
        $mobileCode->ip = $ip;
        $mobileCode->expired_at = Carbon::now()->addMinutes($exception);
        $mobileCode->type = $type;
        return $mobileCode;
    }

    public function refrecode($exception, $ip)
    {
        $this->code = static::genCode();
        $this->ip = $ip;
        $this->expired_at = Carbon::now()->addMinutes($exception);
        return $this;
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected static function genCode()
    {
        return random_int(100000, 999999);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'mobile', 'mobile');
    }

    public function changeState($status)
    {
        $this->state = $status;
        return $this;
    }
}
