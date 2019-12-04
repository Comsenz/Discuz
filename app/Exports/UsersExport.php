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

    protected function data(){

        return User::select('users.id as id', 'users.username',  'user_profiles.sex', 'users.mobile', 'users.adminid', 'users.last_login_ip', 'users.status')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->orderBy('id', 'asc')
            ->get()
            ->each(function ($item, $key) {

                $item->sex = ($item->sex == 1) ? '男' : '女';
                $item->status = ($item->status == 1) ? '正常' : '禁用';

            })
            ->toArray();
    }
}