<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class MobileCode extends Model
{

    const USED_STATE = 1;

    protected $fillable = ['mobile', 'code', 'type', 'exception_at'];


    /**
     * @param $mobile
     * @param $exception
     * @param $type
     * @param $ip
     * @return MobileCode
     * @throws \Exception
     */
    public static function make($mobile, $exception, $type, $ip) {
        $mobileCode = new static();
        $mobileCode->mobile = $mobile;
        $mobileCode->code = static::genCode();
        $mobileCode->ip = $ip;
        $mobileCode->exception_at = Carbon::now()->addMinutes($exception);
        $mobileCode->type = $type;
        return $mobileCode;
    }

    public function refrecode($exception, $ip) {
        $this->code = static::genCode();
        $this->ip = $ip;
        $this->exception_at = Carbon::now()->addMinutes($exception);
        return $this;
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected static function genCode() {
        return random_int(10000, 50000);
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
