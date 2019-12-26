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
        'last_login_ip' => '最后登陆ip',
        'status' => '账号状态',
        'created_at' => '注册时间',
        'openid' => '微信openid',
        'unionid' => '微信unionID',
        'nickname' => '微信昵称',
    ];
}
