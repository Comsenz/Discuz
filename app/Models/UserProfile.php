<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model {

    public $timestamps = false;

    protected $keys = 'example_key';

    protected $fillable = ['id', 'user_id','sex','icon'];

}