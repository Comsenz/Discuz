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

    public function cannot($ability)
    {
        return false;
    }

    public function hasPermission($ability)
    {
        return false;
    }

    /**
     *
     * @return static
     */
    public static function creation() {
        // 实例一个圈子模型
        $user = new static;

        // 设置模型属性值
        $user->id = 1;

        // 返回模型
        return $user;
    }
}
