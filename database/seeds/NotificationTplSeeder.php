<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

use Illuminate\Database\Seeder;
use App\Models\NotificationTpl;

class NotificationTplSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notificationTpl = new NotificationTpl();
        $notificationTpl->truncate();

        // 获取系统通知
        $system = $this->systemData();

        // 获取微信通知
        $wechat = $this->wechatData();

        // 新增新通知(叠加)
        $newData = $this->newData();

        $datas = array_merge($system, $wechat, $newData);

        $notificationTpl->insert($datas);
    }

    /**
     * 微信通知
     *
     * @return array
     */
    public function wechatData()
    {
        return [
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '新用户注册并加入后',
                'title' => '微信注册通知',
                'content' => $this->getWechatFormat([
                    'first' => '你好，欢迎加入{sitename}',
                    'keyword1' => '{username}',
                    'keyword2' => '{dateline}',
                    'remark' => '请你在发表言论时，遵守当地法律法规。祝你在这里玩的愉快',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{sitename}' => '站点名称',
                    '{username}' => '用户名',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '注册审核通过通知',
                'title' => '微信注册审核通知',
                'content' => $this->getWechatFormat([
                    'first' => '你的注册申请已审核通过',
                    'keyword1' => '{username}',
                    'keyword2' => '{dateline}',
                    'remark' => '祝你在这里玩的愉快',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '注册审核不通过通知',
                'title' => '微信注册审核通知',
                'content' => $this->getWechatFormat([
                    'first' => '你的注册申请审核不通过',
                    'keyword1' => '{username}',
                    'keyword3' => '{reason}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击重新提交申请',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{dateline}' => '时间',
                    '{redirecturl}' => '跳转地址',
                    '{reason}' => '原因',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容审核通过通知',
                'title' => '微信内容审核通知',
                'content' => $this->getWechatFormat([
                    'first' => '你发布的内容已审核通过',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{content}' => '内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容审核不通过通知',
                'title' => '微信内容审核通知',
                'content' => $this->getWechatFormat([
                    'first' => '你发布的内容审核不通过',
                    'keyword1' => '{content}',
                    'keyword2' => '{reason}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{content}' => '内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                    '{reason}' => '原因',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容删除通知',
                'title' => '微信内容通知',
                'content' => $this->getWechatFormat([
                    'first' => '你发布的内容已删除',
                    'keyword1' => '{content}',
                    'keyword2' => '{reason}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{content}' => '内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                    '{reason}' => '原因',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容精华通知',
                'title' => '微信内容通知',
                'content' => $this->getWechatFormat([
                    'first' => '你发布的内容已设为精华',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{content}' => '内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容置顶通知',
                'title' => '微信内容通知',
                'content' => $this->getWechatFormat([
                    'first' => '你发布的内容已置顶',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{content}' => '内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容修改通知',
                'title' => '微信内容通知',
                'content' => $this->getWechatFormat([
                    'first' => '你发布的内容已被修改',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{content}' => '内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '帐号禁用通知',
                'title' => '微信用户通知',
                'content' => $this->getWechatFormat([
                    'first' => '你的帐号已禁用',
                    'keyword1' => '{username}',
                    'keyword2' => '{reason}',
                    'keyword3' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                    '{reason}' => '原因',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '用户解除禁用通知',
                'title' => '微信用户通知',
                'content' => $this->getWechatFormat([
                    'first' => '你的帐号已解除禁用',
                    'keyword1' => '{username}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '用户角色调整通知',
                'title' => '微信角色通知',
                'content' => $this->getWechatFormat([
                    'first' => '你的角色已变更',
                    'keyword1' => '{username}',
                    'keyword2' => '{oldgroupname}',
                    'keyword3' => '{newgroupname}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{oldgroupname}' => '原角色',
                    '{newgroupname}' => '新角色',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
        ];
    }

    /**
     * 微信通知 - 数据格式
     *
     * @param $arr
     * @return false|string
     */
    public function getWechatFormat($arr)
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

    /**
     * 系统通知
     *
     * @return array
     */
    public function systemData()
    {
        return [
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '新用户注册并加入后',
                'title' => '欢迎加入{sitename}',
                'content' => '{username}你好，你已经成为{sitename} 的{groupname} ，请你在发表言论时，遵守当地法律法规。祝你在这里玩的愉快。',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{sitename}' => '站点名称',
                    '{groupname}' => '用户组'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '注册审核通过通知',
                'title' => '注册审核通知',
                'content' => '{username}你好，你的注册申请已审核通过。',
                'vars' => serialize([
                    '{username}' => '用户名'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '注册审核不通过通知',
                'title' => '注册审核通知',
                'content' => '{username}你好，你的注册申请审核不通过，原因：{reason}',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{reason}' => '原因'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容审核不通过通知',
                'title' => '内容审核通知',
                'content' => '{username}你好，你发布的内容 "{content}" 审核不通过，原因：{reason}',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{content}' => '内容',
                    '{reason}' => '原因'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容审核通过通知',
                'title' => '内容审核通知',
                'content' => '{username}你好，你发布的内容 "{content}" 审核通过',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{content}' => '内容'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容删除通知',
                'title' => '内容通知',
                'content' => '{username}你好，你发布的内容 "{content} " 已删除，原因：{reason}',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{content}' => '内容',
                    '{reason}' => '原因'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容精华通知',
                'title' => '内容通知',
                'content' => '{username}你好，你发布的内容 "{content}" 已设为精华',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{content}' => '内容'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容置顶通知',
                'title' => '内容通知',
                'content' => '{username}你好，你发布的内容 "{content}" 已置顶',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{content}' => '内容'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '内容修改通知',
                'title' => '内容通知',
                'content' => '{username}你好，你发布的内容 "{content}" 已被修改',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{content}' => '内容'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '用户禁用通知',
                'title' => '用户通知',
                'content' => '{username}你好，你的帐号已禁用，原因：{reason}',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{reason}' => '原因'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '用户解除禁用通知',
                'title' => '用户通知',
                'content' => '{username}你好，你的帐号已解除禁用',
                'vars' => serialize([
                    '{username}' => '用户名'
                ])
            ],
            [
                'status' => 1,
                'type' => 0,
                'type_name' => '用户角色调整通知',
                'title' => '角色通知',
                'content' => '{username}你好，你的角色由{oldgroupname}变更为{newgroupname}',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{oldgroupname}' => '老用户组',
                    '{newgroupname}' => '新用户组'
                ])
            ],
        ];
    }

    /**
     * 系统通知 - 特殊处理通知格式
     *
     * @param $arr
     * @return string
     */
    public function getSystemFormat($arr)
    {
        return json_encode($arr);
    }

    /**
     * 新增通知数据
     * (在数组最后以叠加的形式初始化)
     *
     * @return array
     */
    public function newData()
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | 新增系统通知
            |--------------------------------------------------------------------------
            */

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
                'type_name' => '内容打赏通知',
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

            /*
            |--------------------------------------------------------------------------
            | 新增微信通知
            |--------------------------------------------------------------------------
            */

            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容回复通知',
                'title' => '微信内容通知',
                'content' => $this->getWechatFormat([
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
                'content' => $this->getWechatFormat([
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
                'type_name' => '内容打赏通知',
                'title' => '微信内容通知',
                'content' => $this->getWechatFormat([
                    'first' => '{username}打赏了你{money}',
                    'keyword1' => '{content}',
                    'keyword2' => '{dateline}',
                    'remark' => '点击查看',
                    'redirect_url' => '{redirecturl}',
                ]),
                'vars' => serialize([
                    '{username}' => '打赏人用户名',
                    '{money}' => '金额',
                    '{content}' => '打赏内容',
                    '{dateline}' => '通知时间',
                    '{redirecturl}' => '跳转地址',
                ])
            ],
            [
                'status' => 0,
                'type' => 1,
                'type_name' => '内容@通知',
                'title' => '微信内容通知',
                'content' => $this->getWechatFormat([
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
        ];
    }

}
