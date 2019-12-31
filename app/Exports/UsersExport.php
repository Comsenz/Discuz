<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Exports;

class UsersExport extends Export
{
    public $columnMap = [
        'id' => '用户ID',
        'username' => '用户名',
        'mobile' => '手机号',
        'status' => '账号状态',
        'sex' => '性别',
        'groups' => '用户组名',
        'openid' => '微信openid',
        'unionid' => '微信unionID',
        'nickname' => '微信昵称',
        'created_at' => '注册时间',
        'register_ip' => '注册IP',
        'updated_at' => '最后登录时间',
        'last_login_ip' => '最后登陆ip',
    ];
}
