<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NotificationTpl
 *
 * @property int $id
 * @property int $status
 * @property int $type
 * @property int $type_name
 * @property string $title
 * @property string $content
 * @property string $vars
 * @property string $template_id
 */
class NotificationTpl extends Model
{
    const OPEN = 1;

    const SYSTEM_NOTICE = 0; // 数据库（系统）通知

    const WECHAT_NOTICE = 1; // 微信通知

    public $timestamps = false;

    public $table = 'notification_tpls';

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
         * 公众号限制 (keyword最少2个 最多是5个)
         */
        for ($i = 3; $i < 5; $i++) {
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
            25 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容回复通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            26 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容点赞通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            27 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容支付通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            28 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容@通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            29 => [
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
            30 => [
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
            31 => [
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
            32 => [
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
            33 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '提现通知',
                'title' => '财务通知',
                'content' => '',
                'vars' => '',
            ],
            34 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '提现失败通知',
                'title' => '财务通知',
                'content' => '',
                'vars' => '',
            ],
            35 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '提现通知',
                'title' => '微信财务通知',
                'content' => self::getWechatFormat([
                    'first' => '你发起的提现请求',
                    'keyword1' => '{money}',
                    'keyword2' => '{dateline}',
                    'keyword3' => '{withdrawalStatus}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{money}' => '金额',
                    '{dateline}' => '申请时间',
                    '{withdrawalStatus}' => '提现状态',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            36 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '提现失败通知',
                'title' => '微信财务通知',
                'content' => self::getWechatFormat([
                    'first' => '你发起的提现请求',
                    'keyword1' => '{money}',
                    'keyword2' => '{dateline}',
                    'keyword3' => '{withdrawalStatus}',
                    'keyword4' => '{reason}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{money}' => '金额',
                    '{dateline}' => '申请时间',
                    '{withdrawalStatus}' => '提现状态',
                    '{reason}' => '原因',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            37 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '分成收入通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            38 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '分成收入通知',
                'title' => '微信内容通知',
                'content' => self::getWechatFormat([
                    'first' => '你收到了{username}的分成{money}',
                    'keyword1' => '{content}',
                    'keyword2' => '{ordertype}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '支付用户名',
                    '{money}' => '分成金额',
                    '{content}' => '内容',
                    '{ordertype}' => '支付类型',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            39 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '问答提问通知',
                'title' => '问答通知',
                'content' => '',
                'vars' => '',
            ],
            40 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '问答提问通知',
                'title' => '微信问答通知',
                'content' => self::getWechatFormat([
                    'first' => '{username}向你提问',
                    'keyword1' => '{content}',
                    'keyword2' => '{money}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '问答人',
                    '{content}' => '主题(提问)内容',
                    '{money}' => '问答价格',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            41 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '问答回答通知',
                'title' => '问答通知',
                'content' => '',
                'vars' => '',
            ],
            42 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '问答回答通知',
                'title' => '微信问答通知',
                'content' => self::getWechatFormat([
                    'first' => '{username}回答了你',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '问答人',
                    '{content}' => '回答内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            43 => [
                'status' => 1,
                'type' => 0,
                'type_name' => '过期通知',
                'title' => '内容通知',
                'content' => '',
                'vars' => '',
            ],
            44 => [
                'status' => 0,
                'type' => 1,
                'type_name' => '过期通知',
                'title' => '微信内容通知',
                'content' => self::getWechatFormat([
                    'first' => '{username}',
                    'keyword1' => '{detail}',
                    'keyword2' => '{content}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '您的问题超时未收到回答',
                    '{detail}' => '返还金额xx',
                    '{content}' => '内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ]
        ];
    }
}
