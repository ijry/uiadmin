CREATE TABLE `ia_core_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `module` varchar(255) NOT NULL COMMENT '模块名称',
  `path` varchar(255) NOT NULL COMMENT '路由路径',
  `pmenu` varchar(255) NOT NULL DEFAULT '' COMMENT '父菜单',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `menu_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '菜单类型1导航2按钮3仅接口',
  `is_vadypage` int(1) NOT NULL DEFAULT '0' COMMENT '是否动态页面',
  `api_prefix` varchar(255) NOT NULL DEFAULT '' COMMENT '接口前缀',
  `api_suffix` varchar(255) NOT NULL DEFAULT '' COMMENT '接口后缀',
  `api_method` varchar(255) NOT NULL DEFAULT '' COMMENT '接口请求方法',
  `is_hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `sortnum` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

INSERT INTO `ia_core_menu` (`id`, `module`, `path`, `pmenu`, `title`, `menu_type`, `is_vadypage`, `api_prefix`, `api_suffix`, `api_method`, `is_hide`, `sortnum`)
VALUES
	(1, 'core', 'developer', '', '开发者', 0, 0, '', '', '', 0, 99),
	(2, 'core', '/core/menu/lists', 'developer', '菜单列表', 3, 1, 'v1', '', 'GET', 0, 0),
	(3, 'core', '/core/role/trees', 'system', '权限管理', 1, 1, 'v1', '', 'GET', 0, 0),
	(4, 'core', '/core/user/lists', 'system', '用户列表', 1, 1, 'v1', '', 'GET', 0, 0),
	(5, 'core', '/core/user/edit', '/core/user/lists', '修改用户', 2, 1, 'v1', '/:id', 'GET|PUT', 0, 0),
	(6, 'core', '/core/user/delete', '/core/user/delete', '删除用户', 2, 1, 'v1', '/:id', 'DELETE', 0, 0),
	(7, 'core', '/core/user/add', '/core/user/lists', '添加用户', 2, 1, 'v1', '', 'GET|POST', 0, 0),
	(8, 'core', '/core/config/lists', 'system', '系统配置', 1, 1, 'v1', '', 'GET', 0, 0),
	(9, 'core', 'system', '', '系统', 0, 0, '', '', '', 0, 1),
	(10, 'core', '/core/menu/trees', 'developer', '菜单管理', 1, 1, 'v1', '', 'GET', 0, 0);


CREATE TABLE `ia_core_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户UID',
  `nickname` varchar(128) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `username` varchar(128) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(128) NOT NULL DEFAULT '' COMMENT '用户密码',
  `avatar` varchar(256) NOT NULL COMMENT '头像地址',
  `extend_info` text COMMENT '用户扩展信息',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态,1正常,0待审核,-1禁用',
  `roles` varchar(256) NOT NULL DEFAULT '' COMMENT '用户拥有的角色',
  `register_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户注册时间',
  `delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户帐号信息表';

INSERT INTO `ia_core_user` (`id`, `nickname`, `username`, `password`, `avatar`, `extend_info`, `status`, `roles`, `register_time`, `delete_time`)
VALUES
	(1, '超级管理员', 'admin', '6efb207798cfc6c7819f99cbe03132b0', '1', NULL, 1, '', 0, 0);


CREATE TABLE `ia_core_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级',
  `name` varchar(255) NOT NULL COMMENT '角色名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '角色标题',
  `view_auth` longtext COMMENT '视图权限',
  `api_auth` longtext COMMENT '接口权限',
  `sortnum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色表';

INSERT INTO `ia_core_role` (`id`, `pid`, `name`, `title`, `view_auth`, `api_auth`, `sortnum`, `status`, `delete_time`)
VALUES
	(1, 0, 'super_admin', '超级管理员', '', NULL, 0, 0, 0),
	(2, 1, 'admin', '管理员', '', NULL, 0, 0, 0),
	(3, 2, 'operation', '运营部', '', NULL, 0, 0, 0);


CREATE TABLE `ia_core_identity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT 'UID',
  `identity_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '账号类型',
  `identity_group` varchar(128) NOT NULL DEFAULT '' COMMENT '账号分组',
  `identifier` varchar(128) NOT NULL DEFAULT '' COMMENT '帐号/openid',
  `verified` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已验证',
  `is_oauth2` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否OAuth2登录',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登陆凭证信息表';

INSERT INTO `ia_core_identity` (`id`, `uid`, `identity_type`, `identity_group`, `identifier`, `verified`, `is_oauth2`, `create_time`, `delete_time`)
VALUES
	(1, 1, 1, '+86', '13282171975', 1, 0, 0, 0),
	(2, 1, 2, '', 'ijry@qq.com', 1, 0, 0, 0);


CREATE TABLE `ia_core_userlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'UID',
  `event_type` int(2) unsigned NOT NULL DEFAULT '0' COMMENT '事件类型：1注册2登陆3修改密码4修改头像5修改昵称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `from_ip` varchar(20) NOT NULL COMMENT 'IP',
  `from_country` varchar(20) NOT NULL DEFAULT '' COMMENT '国家地区',
  `from_city` varchar(127) NOT NULL DEFAULT '' COMMENT '城市',
  `from_isp` varchar(127) NOT NULL DEFAULT '' COMMENT 'ISP',
  `client_device` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '客户端设备名称',
  `client_os` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '客户端操作系统',
  `client_os_version` varchar(16) NOT NULL DEFAULT '' COMMENT '客户端操作系统版本',
  `client_type` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '客户端类型，1后台管理员/2h5/3pc/4ios/5android/6wxweb/7wxapp/8aliapp/9bdapp/10ttapp',
  `client_version` varchar(16) NOT NULL DEFAULT '' COMMENT '登陆客户端版本',
  `user_agent` text COMMENT '用户代理',
  `create_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改者UID',
  `event_result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '事件结果',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '原因',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户安全记录';
