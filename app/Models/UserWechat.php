<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create($user)
 * @method static where(...$params)
 */
class UserWechat extends Model
{

    protected $fillable = ['user_id', 'mp_openid','dev_openid','min_openid','nickname','sex', 'city', 'headimgurl', 'unionid'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
