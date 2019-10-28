<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWechat extends Model {

    public $timestamps = false;

    protected $keys = 'example_key';

    protected $fillable = ['id', 'openid','nickname','sex'];



}