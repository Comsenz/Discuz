<?php

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
        $notificationTpl->insert([
            [
                'status' => 1,
                'type_name' => '新用户注册并加入后',
                'title' => '欢迎加入【{sitename}】',
                'content' => '【{username}】你好，你已经成为【{sitename}】 的【{groupname}】 ，请您在发表言论时，遵守当地法律法规。祝你在这里玩的愉快。',
                'vars' => serialize([
                     '{username}' => '用户名',
                    '{sitename}' => '站点名称',
                    '{groupname}' => '用户组'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '注册审核通过通知',
                'title' => '注册审核通知',
                'content' => '【{username}】你好，你的注册申请已审核通过。',
                'vars' => serialize([
                     '{username}' => '用户名'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '注册审核不通过通知',
                'title' => '注册审核通知',
                'content' => '【{username}】你好，你的注册申请审核不通过，原因：【{reason}】',
                'vars' => serialize([
                     '{username}' => '用户名',
                     '{reason}' => '原因'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '内容审核不通过通知',
                'title' => '内容审核通知',
                'content' => '【{username}】你好，你的发布的内容 "【{content}】" 审核不通过，原因：【{reason}】',
                'vars' => serialize([
                     '{username}' => '用户名',
                     '{content}' => '内容',
                     '{reason}' => '原因'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '内容审核通过通知',
                'title' => '内容审核通知',
                'content' => '【{username}】你好，你的发布的内容 "【{content}】" 审核通过',
                'vars' => serialize([
                     '{username}' => '用户名',
                     '{content}' => '内容'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '内容删除通知',
                'title' => '内容删除通知',
                'content' => '【{username}】你好，你的发布的内容 "【{content}】 " 已删除，原因：【{reason}】',
                'vars' => serialize([
                     '{username}' => '用户名',
                     '{content}' => '内容',
                     '{reason}' => '原因'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '内容精华通知',
                'title' => '内容精华通知',
                'content' => '【{username}】你好，你的发布的内容 "【{content}】" 已设为精华',
                'vars' => serialize([
                     '{username}' => '用户名',
                     '{content}' => '内容'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '内容置顶通知',
                'title' => '内容置顶通知',
                'content' => '【{username}】你好，你的发布的内容 "【{content}】" 已置顶',
                'vars' => serialize([
                     '{username}' => '用户名',
                     '{content}' => '内容'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '内容修改通知',
                'title' => '内容修改通知',
                'content' => '【{username}】你好，你的发布的内容 "【{content}】" 已被修改',
                'vars' => serialize([
                     '{username}' => '用户名',
                     '{content}' => '内容'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '帐号禁用通知',
                'title' => '帐号禁用通知',
                'content' => '【{username}】你好，你的账号已禁用，原因：【{reason}】',
                'vars' => serialize([
                     '{username}' => '用户名',
                     '{reason}' => '原因'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '用户解除禁用通知',
                'title' => '解除禁用通知',
                'content' => '【{username}】你好，你的账号已解除禁用',
                'vars' => serialize([
                     '{username}' => '用户名'
                ])
            ],
            [
                'status' => 1,
                'type_name' => '用户角色调整通知',
                'title' => '角色调整通知',
                'content' => '【{username}】你好，你的角色由【{oldgroupname}】变更为【{newgroupname}】',
                'vars' => serialize([
                    '{username}' => '用户名',
                    '{oldgroupname}' => '老用户组',
                    '{newgroupname}' => '新用户组'
                ])
            ]
        ]);
    }
}
