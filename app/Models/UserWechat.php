<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create($user)
 * @method static where(...$params)
 */
class UserWechat extends Model {

    protected $primaryKey = 'user_id';

    protected $fillable = ['user_id', 'openid','nickname','sex', 'city', 'headimgurl', 'unionid'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
