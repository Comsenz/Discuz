<?php

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use App\Events\UserProfile\Created;

class UserProfile extends Model {
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    public $timestamps = false;

    protected $keys = 'example_key';

    protected $fillable = ['id', 'user_id','sex','icon'];

    /**
     * 创建圈子.
     *
     * @param $name        圈子名称
     * @param $icon        圈子图标
     * @param $description 圈子介绍
     * @param $property    圈子属性
     * @param $ipAddress   创建的IP地址
     * @return static
     */
    public static function creation(
        $user_id,
        $icon,
        $sex,
        $ipAddress
    ) {
        // 实例一个圈子模型
        $userProfile = new static;

        // 设置模型属性值
        $userProfile->user_id = $user_id;
        $userProfile->icon = $icon;
        $userProfile->sex = $sex;
        $userProfile->ip = $ipAddress;

        // 暂存需要执行的事件
        $userProfile->raise(new Created($userProfile));

        // 返回圈子模型
        return $userProfile;
    }
}