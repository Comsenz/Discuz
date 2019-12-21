<?php

use Illuminate\Database\Seeder;
use App\Models\GroupPermission;

class GroupPermissionTableSeeder extends Seeder
{
    /*
    * 默认用户组 1 为超级管理员有以下的所有权限
    * @var array
    */
    protected $permissions = [
        // 用户
        'user.view' => [7, 10],                 // 查看某个用户信息权限
        'user.view.mobile' => [],               // 是否能查看用户真实手机号
        'user.edit' => [],                      // 编辑某个用户信息权限，除自己以外
        'user.delete' => [],                    // 删除某个用户信息权限
        'viewUserList' => [7, 10],              // 查看用户列表权限

        // 用户组
        'group.create' => [],                   // 添加用户组权限
        'group.delete' => [],                   // 删除用户组权限

        // 分类
        'createCategory' => [],                 // 创建分类
        'category.delete' => [],                // 删除分类
        'category.edit' => [],                // 修改分类

        // 主题
        'viewThreads' => [7, 10],               // 查看主题
        'createThread' => [10],                 // 发布主题
        'thread.reply' => [10],                 // 回复主题

        // 回复
        'deletePosts' => [10],                  // 删除回复

        // 附件
        'attachment.createAttachment' => [10],  // 上传附件
        'attachment.delete' => [10],            // 删除附件

        // 敏感词
        'stopWord.create' => [],        // 创建敏感词
        'stopWord.delete' => [],        // 删除敏感词

        // 站点
        'viewSiteInfo' => [],                   // 查看站点信息权限
        'checkVersion' => [],                   // 检查是否有新版权限

        // 订单
        'order.create' => [7, 10],              // 创建订单
        'order.viewList' => [],                 // 订单总列表

        // 钱包
        'wallet.update' => [],                  // 更新钱包
        'wallet.viewList' => [],                // 总钱包信息列表
        'wallet.logs.viewList' => [],           // 钱包动账记录总列表

        // 提现
        'cash.create' => [7, 10],               // 申请提现
        'cash.review' => [],                    // 提现审核
        'cash.viewList' => [],                  // 提现总列表
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = collect($this->permissions)->map(function ($value, $key) {
            return collect($value)->map(function($value) use ($key) {
                return [
                    'group_id' => $value,
                    'permission' => $key
                ];
            });
        })->reduce(function ($value, $item) {
            return $item->merge($value);
        });

        $settings = new GroupPermission();
        $settings->truncate();
        $settings->insert($data->toArray());
    }
}
