<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class NotificationTpl extends Model
{
    const OPEN = 1;

    public $timestamps = false;

    /**
     * 枚举 - type
     *
     * 通知类型: 0系统 1微信 2短信
     * @var array
     */
    protected static $status = [
        'database' => 0,
        'wechat' => 1,
        'sms' => 2, // 待定暂未使用
    ];

    /**
     * 根据 值/类型 获取对应值
     *
     * @param mixed $mixed
     * @return mixed
     */
    public static function enumType($mixed)
    {
        $arr = static::$status;

        if (is_numeric($mixed)) {
            return array_search($mixed, $arr);
        }

        return $arr[$mixed];
    }
}
