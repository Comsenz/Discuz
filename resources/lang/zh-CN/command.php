<?php

return [
    'rsa:gen' => '生成 OAUTH2 private.key 和 public.key',
    'key:generate' => '生成站点唯一key，用于HASH',
    'storage:link' => '创建从“公共/存储”到“存储/应用/公共”的符号链接',

    // clear
    'clear:avatar' => '清理本地/COS未使用的头像',
    'clear:attachment' => '清理本地/COS未使用的附件',
    'clear:video' => '清理未发布的主题视频',
    'clear:question' => '返还过期未回答的问答金额',

    // upgrade
    'upgrade:avatar' => '更新用户头像信息',
    'upgrade:ordersExpiredAt' => '初始化付费注册订单的过期时间',
    'upgrade:category-permission' => '初始化分类权限',
    'upgrade:videoSize' => '初始化转码成功的视频宽高、时长',
    'upgrade:notice' => '更新迭代/新增通知类型数据格式',
    'upgrade:postContent' => '初始化帖子内容，把原内容转为块编辑器的json数据。需要在迁移之前执行。',
];
