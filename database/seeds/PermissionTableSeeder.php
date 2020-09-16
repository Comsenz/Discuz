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

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * 默认用户组 1 为超级管理员有以下的所有权限
     *
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
        'category.edit' => [],                  // 修改分类

        // 默认分类下的权限
        'category1.viewThreads' => [7, 10],     // 默认分类看帖权限
        'category1.createThread' => [10],       // 默认分类发帖权限
        'category1.replyThread' => [10],        // 默认分类发回复权限

        // 主题
        'viewThreads' => [7, 10],               // 查看主题列表
        'createThread' => [10],                 // 发布文字
        'createThreadLong' => [10],             // 发布帖子
        'createThreadImage' => [],              // 发布图片
        'createThreadVideo' => [],              // 发布视频
        'createThreadAudio' => [],              // 发布语音
        'thread.rename' => [],                  // 修改主题标题
        'thread.reply' => [10],                 // 回复主题
        'thread.favorite' => [10],              // 收藏主题
        'createThreadWithCaptcha' => [],        // 发布主题验证验证码
        'publishNeedRealName' => [],            // 发布内容需先实名认证
        'publishNeedBindPhone' => [],           // 发布内容需先绑定手机

        // 回复
        'thread.viewPosts' => [7, 10],          // 查看主题详情
        'thread.hidePosts' => [],               // 删除回复
        'thread.likePosts' => [10],             // 点赞回复

        // 内容审核
        'thread.approvePosts' => [],            // 审核主题或回复

        // 回收站
        'viewTrashed' => [],                    // 查看回收站

        // 附件
        'attachment.create.0' => [10],          // 上传附件
        'attachment.create.1' => [10],          // 上传图片
        'attachment.delete' => [],              // 删除附件

        // 敏感词
        'stopWord.create' => [],                // 创建敏感词
        'stopWord.delete' => [],                // 删除敏感词

        // 站点
        'viewSiteInfo' => [],                   // 查看站点信息权限
        'checkVersion' => [],                   // 检查是否有新版权限
        'setting.site' => [],                   // 上传站点logo

        // 订单
        'order.create' => [6, 10],              // 创建订单
        'order.viewList' => [],                 // 订单总列表

        // 钱包
        'wallet.update' => [],                  // 更新钱包
        'wallet.viewList' => [],                // 总钱包信息列表
        'wallet.logs.viewList' => [],           // 钱包动账记录总列表
        'trade.pay.order' => [6, 10],           // 支付订单

        // 提现
        'cash.create' => [10],                  // 申请提现
        'cash.review' => [],                    // 提现审核
        'cash.viewList' => [],                  // 提现总列表

        // 邀请
        'createInvite' => [],                   // 发起邀请

        // 财务
        'statistic.financeProfile' => [],       // 财务概况
        'statistic.financeChart' => [],         // 财务图表

        // 短消息
        'dialog.create' => [10],                 // 创建会话、会话消息

        // 关注
        'userFollow.create' => [10],           // 创建关注
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = collect($this->permissions)->map(function ($value, $key) {
            return collect($value)->map(function ($value) use ($key) {
                return [
                    'group_id' => $value,
                    'permission' => $key
                ];
            });
        })->reduce(function ($value, $item) {
            return $item->merge($value);
        });

        Permission::query()->truncate();
        Permission::query()->insert($data->toArray());
    }
}
