CREATE TABLE `settings` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设置表key',
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设置表value',
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default' COMMENT '分组tag',
  PRIMARY KEY (`key`,`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stop_words` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `ugc` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `find` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `replacement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime(2) DEFAULT NULL,
  `updated_at` datetime(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户组名称',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '颜色',
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'icon类',
  `default` tinyint(1) NOT NULL COMMENT '是否为注册默认组',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `group_permission` (
  `group_id` int(10) unsigned NOT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`group_id`,`permission`),
  CONSTRAINT `group_permission_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `group_user` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `last_posted_user_id` int(10) unsigned DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `post_count` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  `like_count` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_user_id` int(10) unsigned DEFAULT NULL,
  `is_approved` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_essence` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `posts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `thread_id` int(10) unsigned DEFAULT NULL,
  `reply_id` int(10) unsigned DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reply_count` int(10) unsigned NOT NULL DEFAULT '0',
  `like_count` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_user_id` int(10) unsigned DEFAULT NULL,
  `is_first` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_sn` char(22) CHARACTER SET utf8mb4 NOT NULL,
  `payment_sn` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `payee_id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL,
  `thread_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `platform` smallint(5) UNSIGNED DEFAULT NULL,
  `payment_type` smallint(5) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pay_notify` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_sn` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `trade_no` varchar(64) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` char(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `mobile_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `register_ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `thread_count` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `joined_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_wechats` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nickname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `province` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `country` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `headimgurl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `privilege` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `unionid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 分类表
CREATE TABLE `classify` (
    `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类id',
    `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
    `description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类说明',
    `icon` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类图标URL',
    `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '显示顺序',
    `property` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '属性：0:正常 1:首页展示',
    `threads` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '主题数',
    `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '更新IP',
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 邀请码表
CREATE TABLE `invites` (
   `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
   `user_group_id` int(10) unsigned NOT NULL COMMENT '默认用户组ID',
   `code` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邀请码',
   `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请码生效时间',
   `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请码结束时间',
   `user_id` int(10) unsigned NOT NULL COMMENT '邀请用户ID',
   `to_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被邀请用户ID',
   `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '邀请码状态',
   `created_at` datetime NOT NULL,
   `updated_at` datetime NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 附件表
CREATE TABLE `attachments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `post_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复ID',
  `is_gallery` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是帖子图片',
  `is_remote` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `attachment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件系统生成的名称',
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件原名称',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_type` char(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件类型',
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 用户详情
CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `sex` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '3' COMMENT '1男2女',
  `icon` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `mobile_codes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '5位数字验证码',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '默认0，登录：1',
  `state` tinyint(1) NOT NULL DEFAULT '0',
   `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `exception_at` datetime NOT NULL COMMENT '过期时间',
  `created_at` datetime NOT NULL COMMENT '发送时间',
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 用户钱包
CREATE TABLE `user_wallets` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `available_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '可用金额',
  `freeze_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `wallet_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '钱包状态:0正常，1冻结提现',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户钱包表';

-- 用户提现
CREATE TABLE `user_wallet_cash` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cash_sn` bigint(20) UNSIGNED NOT NULL,
  `cash_charge` decimal(10,2) UNSIGNED NOT NULL,
  `cash_actual_amount` decimal(10,2) UNSIGNED NOT NULL,
  `cash_apply_amount` decimal(10,2) UNSIGNED NOT NULL,
  `cash_status` tinyint(3) UNSIGNED NOT NULL COMMENT '提现状态：1：待审核，2：审核通过，3：审核不通过，4：待打款， 5，已打款， 6：打款失败',
  `remark` varchar(255) DEFAULT NULL,
  `trade_time` datetime DEFAULT NULL COMMENT '交易时间',
  `trade_no` varchar(64) DEFAULT NULL COMMENT '交易号',
  `error_code` varchar(64) DEFAULT NULL COMMENT '错误代码',
  `error_message` varchar(255) DEFAULT NULL COMMENT '交易失败原因',
  `refunds_status` tinyint(3) UNSIGNED DEFAULT '0' COMMENT '返款状态，0未返款，1已返款',
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4  COLLATE=utf8mb4_unicode_ci;

-- 用户钱包动账记录
CREATE TABLE `user_wallet_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `change_available_amount` decimal(10,2) NOT NULL,
  `change_freeze_amount` decimal(10,2) NOT NULL,
  `change_type` smallint(5) UNSIGNED NOT NULL,
  `change_desc` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `log_able_id` int(10) unsigned NOT NULL,
  `log_able_type` char(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `threads` ADD `category_id` INT(10) UNSIGNED NOT NULL AFTER `last_posted_user_id`;

ALTER TABLE `posts` CHANGE `reply_id` `reply_post_id` INT(10) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `posts` ADD `reply_user_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `reply_post_id`;

ALTER TABLE `operation_log` ADD COLUMN `user_id` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '操作用户 id' AFTER `id`;

-- 2019-12-11
ALTER TABLE `orders`
	CHANGE COLUMN `order_sn` `order_sn` CHAR(22) NOT NULL DEFAULT '0' COMMENT '订单编号' AFTER `id`;

-- 2019-12-18 11:42:37
ALTER TABLE `attachments` ADD `uuid` VARCHAR(100)  NOT NULL  DEFAULT ''  AFTER `id`;

-- 2019-12-18 15:52:08
ALTER TABLE `users` ADD `expired_at` DATETIME  NULL  AFTER `joined_at`;

-- 2019-12-18 15:50:00 登陆密码错误次数记录表
CREATE TABLE `user_login_fail_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志 id',
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip 地址',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户 id',
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户名',
  `count` tinyint(4) NOT NULL DEFAULT '1' COMMENT '错误次数',
  `updated_at` datetime NOT NULL COMMENT '更新时间',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `user_login_fail_log_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='登陆密码错误次数记录表';

-- 2019-12-19 验证码过期时间字段
ALTER TABLE `mobile_codes` CHANGE `exception_at` `expired_at` DATETIME NOT NULL COMMENT '过期时间';

-- 2019-12-24 14:08:48 敏感词表
CREATE TABLE `post_mod` (
  `post_id` int(10) unsigned NOT NULL COMMENT '帖子 id',
  `stop_word` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '触发的敏感词，半角逗号隔开',
  PRIMARY KEY (`post_id`),
  CONSTRAINT `post_mod_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2019-12-24 16:03:07 腾讯云敏感图片-附件判断
alter table attachments add column is_approved tinyint(1) not null default '0' comment '是否合法' after is_gallery;

-- 2019-12-25 01:07:15 上一次头像上传时间 防刷敏感图
alter table `users` add column avatar_at datetime null comment '头像修改时间' after avatar;

-- 2019-12-26 15:48:33 用户表添加status 用户状态 0 为正常， 1 为禁用
alter table users add column status tinyint(1) not null default '0' comment '用户状态：0为正常1为禁用' after last_login_ip;

-- 2019-12-26 14:33:11 设置项 value 允许为 null
ALTER TABLE `settings` CHANGE `value` `value` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '设置表value';
