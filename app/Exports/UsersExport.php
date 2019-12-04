<?php
namespace App\Exports;

use App\Models\User;

class UsersExport extends Export {

    public $columnMap = [
        'id' => '用户ID',
        'username' => '用户名',
        'mobile' => '手机号',
        'adminid' => '管理员id',
        'last_login_ip' => '最后登陆ip',
        'status' => '账号状态',
        'sex' => '性别'];

}