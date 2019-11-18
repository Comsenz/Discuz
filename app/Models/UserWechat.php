<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWechat extends Model {

    protected $primaryKey = 'user_id';

    protected $fillable = ['user_id', 'openid','nickname','sex', 'city', 'headimgurl', 'unionid'];

}
