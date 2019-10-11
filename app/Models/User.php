<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @package App\Models
 */
class User extends Model {

    public $timestamps = false;

    protected $fillable = ['id', 'username','password','createtime'];

    protected function setUserLoginPasswordAttr($value)
    {
        return md5($value);
    }
}
