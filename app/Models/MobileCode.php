<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class MobileCode extends Model
{
    protected $fillable = ['mobile', 'code', 'type', 'exception_at'];


    /**
     * @param $mobile
     * @param $exception
     * @param $type
     * @return MobileCode
     * @throws \Exception
     */
    public static function make($mobile, $exception, $type) {
        $mobileCode = new static();
        $mobileCode->mobile = $mobile;
        $mobileCode->code = static::genCode();
        $mobileCode->exception_at = Carbon::now()->addMinutes($exception);
        $mobileCode->type = $type;
        return $mobileCode;
    }

    public function refrecode($exception) {
        $this->code = static::genCode();
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
}
