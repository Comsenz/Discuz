<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_id
 * @property string $mp_openid
 * @property string $dev_openid
 * @property string $min_openid
 * @property string $nickname
 * @property int $sex
 * @property string $province
 * @property string $city
 * @property string $country
 * @property string $headimgurl
 * @property string $privilege
 * @property string $unionid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @method static create($user)
 * @method static where(...$params)
 */
class UserWechat extends Model
{
    protected $fillable = ['user_id', 'mp_openid','dev_openid','min_openid','nickname','sex', 'city', 'province', 'headimgurl', 'unionid'];

    public static function build(array $data)
    {
        $userWechat = new static;
        $userWechat->attributes = $data;
        return $userWechat;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
