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

        /**
         * 允许多个 keyword
         */
        for ($i = 3; $i < 10; $i++) {
            $keyword = 'keyword' . $i;
            if (array_key_exists($keyword, $arr)) {
                $result['data'][$keyword] = [
                    'value' => $arr[$keyword],
                    'color' => '#173177'
                ];
            } else {
                break;
            }
        }

        return json_encode($result);
    }

    /**
     * 追加新增数据值 - 公共
     *
     * @return array[]
     */
    public static function addData()
    {
        // 以数组追加形式新增放入最后
        return [
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容回复通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容点赞通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容支付通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容@通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容回复通知',
                'title' => '微信内容通知',
                'content' => self::getWechatFormat([
                    'first' => '{username}回复了你',
                    'keyword1' => '{content}',
                    'keyword2' => '{subject}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '回复人用户名',
                    '{content}' => '回复内容',
                    '{subject}' => '原文内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容点赞通知',
                'title' => '微信内容通知',
                'content' => self::getWechatFormat([
                    'first' => '{username}点赞了你',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '点赞人用户名',
                    '{content}' => '点赞内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容支付通知',
                'title' => '微信内容通知',
                'content' => self::getWechatFormat([
                    'first' => '{username}支付了你{money}',
                    'keyword1' => '{content}',
                    'keyword2' => '{ordertype}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '支付用户名',
                    '{money}' => '金额',
                    '{content}' => '内容',
                    '{ordertype}' => '支付类型',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容@通知',
                'title' => '微信内容通知',
                'content' => self::getWechatFormat([
                    'first' => '{username}@了你',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '@人用户名',
                    '{content}' => '@内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '提现通知',
                'title' => '财务通知',
                'content' => '',
                'vars' => '',
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '提现失败通知',
                'title' => '财务通知',
                'content' => '',
                'vars' => '',
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '提现通知',
                'title' => '微信财务通知',
                'content' => self::getWechatFormat([
                    'first' => '你发起的提现请求',
                    'keyword1' => '{money}',
                    'keyword2' => '{withdrawalStatus}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{money}' => '金额',
                    '{withdrawalStatus}' => '提现状态',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '提现失败通知',
                'title' => '微信财务通知',
                'content' => self::getWechatFormat([
                    'first' => '你发起的提现请求',
                    'keyword1' => '{money}',
                    'keyword2' => '{withdrawalStatus}',
                    'keyword3' => '{reason}',
                    'keyword4' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{money}' => '金额',
                    '{withdrawalStatus}' => '提现状态',
                    '{reason}' => '原因',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
        ];
    }
}
