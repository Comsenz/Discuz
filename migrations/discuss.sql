CREATE TABLE `settings` (
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`key`)
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
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
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
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `last_posted_user_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `post_count` int(10) unsigned NOT NULL DEFAULT '0',
  `view_count` int(10) unsigned NOT NULL DEFAULT '0',
  `like_count` int(10) unsigned NOT NULL DEFAULT '0',
  `favorite_count` int(10) unsigned NOT NULL DEFAULT '0',
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
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_sn` bigint(20) UNSIGNED NOT NULL,
  `payment_sn` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `user_id` int(10) UNSIGNED NOT NULL,
  `payee_id` int(10) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL,
  `type_id` int(10) UNSIGNED NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `platform` smallint(5) UNSIGNED DEFAULT NULL,
  `payment_type` smallint(5) DEFAULT NULL,
  `remark` text,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `pay_notify` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `payment_sn` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `trade_no` varchar(64) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 NOT NULL,
  `password` char(60) CHARACTER SET utf8 NOT NULL,
  `mobile` char(11) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `adminid` int(11) unsigned NOT NULL DEFAULT '0',
  `unionid` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `login_ip` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_wechats` (
  `id` int(11) NOT NULL,
  `openid` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `nickname` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `sex` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `province` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `city` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `country` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `headimgurl` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `privilege` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `unionid` varchar(30) CHARACTER SET utf8 NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
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
  `attachment` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件系统生成的名称',
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件原名称',
  `file_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_type` char(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件类型',
  `remote` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
  `ip` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建IP',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--用户详情
CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 DEFAULT '',
  `sex` char(1) CHARACTER SET utf8 NOT NULL DEFAULT '3' COMMENT '1男2女',
  `icon` varchar(200) CHARACTER SET utf8 DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;