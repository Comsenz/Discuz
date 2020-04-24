<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationTpl
 *
 * @package App\Models
 * @method static find($id)
 * @method static where($where)
 * @method static insert($id)
 * @method static insertGetId($id)
 * @method static count()
 * @method truncate()
 */
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

    /**
     * 微信通知 - 数据格式
     *
     * @param $arr
     * @return false|string
     */
    public static function getWechatFormat($arr)
    {
        $result = [
            'data' => [
                'first' => [
                    'value' => $arr['first'],
                    'color' => '#173177'
                ],
                'keyword1' => [
                    'value' => $arr['keyword1'],
                    'color' => '#173177'
                ],
                'keyword2' => [
                    'value' => $arr['keyword2'],
                    'color' => '#173177'
                ],
                'remark' => [
                    'value' => $arr['remark'],
                    'color' => '#173177'
                ],
            ],
            'redirect_url' => $arr['redirect_url'],
        ];

        if (array_key_exists('keyword3', $arr)) {
            $key3 = [
                'value' => $arr['keyword3'],
                'color' => '#173177'
            ];
            $result['data']['keyword3'] = $key3;
        }

        return json_encode($result);
    }

}
