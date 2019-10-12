-- 圈子表
CREATE TABLE `pre_circles` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '圈子ID',
    `name` char(50) NOT NULL DEFAULT '' COMMENT '圈子名称',
    `icon` varchar(200) NOT NULL DEFAULT '' COMMENT '圈子图标',
    `description` varchar(200) NOT NULL DEFAULT '' COMMENT '圈子介绍',
    `property` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '属性：0:公开、1:非公开、2:私密',
    `threads` int(10) NOT NULL DEFAULT '0' COMMENT '主题数',
    `digestposts` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '精华数',
    `membernum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '成员数',
    `tag_on` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启标签',
    `ip` char(15) NOT NULL DEFAULT '0' COMMENT '更新IP',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='圈子表';

-- 圈子扩展表
CREATE TABLE `pre_circle_extends` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `circle_id` int(11) unsigned NOT NULL COMMENT '圈子ID',
    `type` char(10) NOT NULL DEFAULT '' COMMENT '类型:月、年等',
    `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '进圈价格',
    `indate_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '属性：0:永久、1:固定日期、2:推移日期',
    `indate_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '到期时间',
    `join_circle_ratio_master` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '加入圈子站长分成比例',
    `read_thread_ratio_master` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '看帖站长分成比例',
    `read_thread_ratio_admin` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '看帖圈主分成比例',
    `give_thread_ratio_master` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '打赏帖子站长分成比例',
    `give_thread_ratio_admin` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '打赏帖子圈主分成比例',
    `ip` char(15) NOT NULL DEFAULT '0' COMMENT '创建IP',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`),
    KEY `circle_id` (`circle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='圈子扩展表';

-- 圈子用户表
CREATE TABLE `pre_circle_users` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `circle_id` int(11) unsigned NOT NULL COMMENT '圈子ID',
    `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
    `circle_group_id` int(11) unsigned NOT NULL COMMENT '用户组ID',
    `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '圈子生效时间',
    `endline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '圈子到期时间',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`),
    KEY `circle_id` (`circle_id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='圈子用户表';

-- 圈子用户组表
CREATE TABLE `pre_circle_groups` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `circle_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '0:系统用户组 非0:圈子用户组',
    `name` char(50) NOT NULL DEFAULT '' COMMENT '用户组名称',
    `privilege` char(255) NOT NULL DEFAULT '' COMMENT '权限',
    `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是默认用户组',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='圈子用户组表';

-- 圈子主题标签表
CREATE TABLE `pre_circle_tags` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `circle_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '圈子ID',
    `name` char(50) NOT NULL DEFAULT '' COMMENT '标签名称',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='圈子主题标签表';

-- 邀请码表
CREATE TABLE `pre_invites` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `user_group_id` int(11) unsigned NOT NULL COMMENT '默认用户组ID',
    `code` char(32) NOT NULL COMMENT '邀请码',
    `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请码生效时间',
    `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请码结束时间',
    `user_id` int(11) unsigned NOT NULL COMMENT '邀请用户ID',
    `to_user_id` int(11) unsigned NOT NULL COMMENT '被邀请用户ID',
    `status` tinyint(1) unsigned NOT NULL COMMENT '邀请码状态',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邀请码表';

-- 主题表
CREATE TABLE `pre_threads` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `circle_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '圈子ID',
    `circle_tag_id` int(11) unsigned NOT NULL COMMENT '圈子标签ID',
    `user_id` int(11) unsigned NOT NULL COMMENT '发表用户ID',
    `last_user_id` int(10) unsigned DEFAULT NULL COMMENT '最后回复用户ID',
    `title` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '标题',
    `price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '售价',
    `views` int(11) unsigned NOT NULL COMMENT '查看数',
    `replies` tinyint(1) unsigned NOT NULL COMMENT '回复数',
    `favtimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
    `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '是否置顶',
    `digest` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '是否精华',
    `invisible` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0:正常 1:审核 2:回收站',
    `attachment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:无附件 1:普通附件 2:图片附件',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='主题表';

-- 回复表
CREATE TABLE `pre_posts` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `thread_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
    `first` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首帖',
    `message` text COMMENT '内容',
    `user_id` int(10) unsigned DEFAULT NULL COMMENT '回复用户ID',
    `invisible` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0:正常 1:审核 2:回收站',
    `comments` tinyint(1) unsigned NOT NULL COMMENT '点评数',
    `likes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
    `ip` char(15) NOT NULL DEFAULT '0' COMMENT '创建IP',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='回复表';

-- 点评表
CREATE TABLE `pre_comments` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复ID',
    `message` text COMMENT '内容',
    `user_id` int(10) unsigned DEFAULT NULL COMMENT '点评用户ID',
    `invisible` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0:正常 1:审核 2:回收站',
    `ip` char(15) NOT NULL DEFAULT '0' COMMENT '创建IP',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='点评表';

-- 点赞表
CREATE TABLE `pre_likes` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复ID',
    `user_id` int(10) unsigned DEFAULT NULL COMMENT '点评用户ID',
    `ip` char(15) NOT NULL DEFAULT '0' COMMENT '创建IP',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='点赞表';

-- 收藏表
CREATE TABLE `pre_favorites` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `thread_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
    `user_id` int(10) unsigned DEFAULT NULL COMMENT '点评用户ID',
    `ip` char(15) NOT NULL DEFAULT '0' COMMENT '创建IP',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏表';

-- 附件表
CREATE TABLE `pre_attachments` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
    `user_id` int(10) unsigned DEFAULT NULL COMMENT '用户ID',
    `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复ID',
    `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '文件系统生成的名称',
    `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
    `file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件原名称',
    `file_size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
    `file_type` char(15) NOT NULL DEFAULT '' COMMENT '文件类型',
    `remote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否远程附件',
    `ip` char(15) NOT NULL DEFAULT '0' COMMENT '创建IP',
    `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='附件表';
