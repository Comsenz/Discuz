<?php

return [
    'rsa:gen' => '生成 OAUTH2 private.key 和 public.key',
    'key:generate' => '生成站点唯一key，用于HASH',
    'storage:link' => '创建从“公共/存储”到“存储/应用/公共”的符号链接',

    // clear
    'clear:avatar' => '清理本地/COS未使用的头像',
    'clear:attachment' => '清理本地/COS未使用的附件',
    'clear:video' => '清理未发布的主题视频',

    // upgrade
    'upgrade:category-permission' => '初始化分类权限',
    'upgrade:videoSize' => '初始化主题视频的宽高',
    'upgrade:notice' => '更新迭代/新增通知类型数据格式',
];
