CREATE TABLE `ia_core_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '模块名称',
  `title` varchar(255) NOT NULL COMMENT '模块标题',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '模块描述',
  `developer` varchar(255) NOT NULL DEFAULT '' COMMENT '开发者',
  `website` varchar(255) NOT NULL DEFAULT '' COMMENT '开发者网站',
  `version` varchar(255) NOT NULL DEFAULT '' COMMENT '版本号',
  `build` varchar(255) NOT NULL DEFAULT '' COMMENT 'build版本',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `sortnum` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台模块表';

INSERT INTO `ia_core_module` (`id`, `name`, `title`, `description`, `developer`, `website`, `version`, `build`, `status`, `sortnum`, `delete_time`)
VALUES
  (1, 'core', '核心', 'InitAdmin/actionphp核心模块', 'jry', 'https://initadmin.net', '0.1.0', 'beta1_201904', 1, 1, 0);


CREATE TABLE `ia_core_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `module` varchar(255) NOT NULL COMMENT '模块名称',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `path` varchar(255) NOT NULL COMMENT '路由路径',
  `pmenu` varchar(255) NOT NULL DEFAULT '' COMMENT '父菜单',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `menu_type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '菜单类型1导航2按钮3仅接口',
  `route_type` varchar(32) NOT NULL DEFAULT '' COMMENT '路由类型',
  `api_prefix` varchar(255) NOT NULL DEFAULT '' COMMENT '接口前缀',
  `api_suffix` varchar(255) NOT NULL DEFAULT '' COMMENT '接口后缀',
  `api_params` varchar(255) NOT NULL DEFAULT '' COMMENT '接口参数',
  `api_method` varchar(255) NOT NULL DEFAULT '' COMMENT '接口请求方法',
  `doc` longtext COMMENT '接口文档',
  `is_hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `sortnum` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

INSERT INTO `ia_core_menu` (`id`, `module`, `icon`, `path`, `pmenu`, `title`, `tip`, `menu_layer`, `menu_type`, `route_type`, `api_prefix`, `api_suffix`, `api_params`, `api_method`, `doc`, `is_hide`, `status`, `sortnum`, `delete_time`)
VALUES
  (1, 'core', 'md-code', '/developer', '', '开发', '一般是开发者采用得到的工具', 'admin', 0, '', '', '', '', '', NULL, 0, 1, 99, 0),
  (2, 'core', '', '/core/menu/lists', '/developer', '菜单列表', '管理后台左侧的菜单导航', 'admin', 3, 'list', 'v1', '', '', 'GET', NULL, 0, 1, 0, 0),
  (3, 'core', '', '/core/role/trees', '/core', '权限管理', '管理系统角色及权限', 'admin', 1, 'list', 'v1', '', '', 'GET', NULL, 0, 1, 2, 0),
  (4, 'core', '', '/core/user/lists', '/core', '用户列表', '系统注册用户列表', 'admin', 1, 'list', 'v1', '', '', 'GET', NULL, 0, 1, 3, 0),
  (5, 'core', '', '/core/user/edit', '/core/user/lists', '修改用户', '修改用户信息', 'admin', 2, 'form', 'v1', '/:id', '', 'GET|PUT', NULL, 0, 1, 0, 0),
  (6, 'core', '', '/core/user/delete', '/core/user/lists', '删除用户', '软删除用户', 'admin', 2, '', 'v1', '/:id', '', 'DELETE', NULL, 0, 1, 0, 0),
  (7, 'core', '', '/core/user/add', '/core/user/lists', '添加用户', '添加用户', 'admin', 2, 'form', 'v1', '', '', 'GET|POST', NULL, 0, 1, 0, 0),
  (8, 'core', '', '/core/config/lists', '/developer', '设置管理', '系统常用设置', 'admin', 1, 'list', 'v1', '', '', 'GET', NULL, 0, 1, 4, 0),
  (9, 'core', 'md-cog', '/core', '', '系统', '核心系统相关功能', 'admin', 0, '', '', '', '', '', NULL, 0, 1, 1, 0),
  (10, 'core', '', '/core/menu/trees', '/developer', '菜单管理', '管理系统后台左侧菜单', 'admin', 1, 'list', 'v1', '', '', 'GET', NULL, 0, 1, 2, 0),
  (11, 'core', '', '/core/role/edit', '/core/role/trees', '修改角色', '修改角色信息及权限', 'admin', 2, 'form', 'v1', '/:id', '', 'GET|PUT', NULL, 0, 1, 0, 0),
  (12, 'core', '', '/core/role/delete', '/core/role/trees', '删除角色', '删除角色', 'admin', 2, '', 'v1', '/:id', '', 'DELETE', NULL, 0, 1, 0, 0),
  (13, 'core', '', '/core/role/add', '/core/role/trees', '添加角色', '添加角色', 'admin', 2, 'form', 'v1', '', '', 'GET|POST', NULL, 0, 1, 0, 0),
  (14, 'core', '', '/core/user_role/lists', '/core/role/trees', '角色成员', '管理角色成员', 'admin', 2, 'list', 'v1', '/:name', '', 'GET', NULL, 0, 1, 0, 0),
  (15, 'core', '', '/core/user_role/add', '/core/user_role/lists', '添加角色成员', '添加一个新角色成员', 'admin', 2, 'form', 'v1', '/:name', '', 'GET|POST', NULL, 0, 1, 0, 0),
  (16, 'core', '', '/core/user_role/delete', '/core/user_role/lists', '删除角色成员', '删除一个角色成员', 'admin', 2, '', 'v1', '/:uid/:name', '', 'DELETE', NULL, 0, 1, 0, 0),
  (17, 'core', '', '/core/index/cleanRuntime', '/core', '清空缓存', '清空服务器端缓存', 'admin', 3, '', 'v1', '', '', 'DELETE', NULL, 0, 1, 0, 0),
  (18, 'core', '', '/core/menu/add', '/core/menu/trees', '添加菜单', '添加后台菜单(接口)', 'admin', 2, 'form', 'v1', '', '', 'GET|POST', NULL, 0, 1, 0, 0),
  (19, 'core', '', '/core/menu/edit', '/core/menu/trees', '修改菜单', '修改后台菜单(接口)', 'admin', 2, 'form', 'v1', '/:id', '', 'GET|PUT', NULL, 0, 1, 0, 0),
  (20, 'core', '', '/core/menu/delete', '/core/menu/trees', '删除菜单', '删除后台菜单(接口)', 'admin', 2, '', 'v1', '/:id', '', 'DELETE', NULL, 0, 1, 0, 0),
  (21, 'core', '', '/core/index/checkUpdate', '/core', '检查更新', '检查InitAdmin新版本', 'admin', 3, '', 'v1', '', '', 'GET', NULL, 0, 1, 0, 0),
  (22, 'core', '', '/core/module/lists', '/developer', '模块管理', '管理系统安装的功能模块', 'admin', 1, 'list', 'v1', '', '', 'GET', NULL, 0, 1, 1, 0),
  (23, 'core', '', '/core/module/add', '/core/module/lists', '创建模块', '创建一个新模块', 'admin', 2, 'form', 'v1', '', '', 'GET|POST', NULL, 0, 1, 0, 0),
  (24, 'core', '', '/core/module/edit', '/core/module/lists', '修改模块', '修改模块信息', 'admin', 2, 'form', 'v1', '/:id', '', 'GET|PUT', NULL, 0, 1, 0, 0),
  (25, 'core', '', '/core/module/export', '/core/module/lists', '导出模块', '导出模块信息便于分享模块', 'admin', 2, 'from', 'v1', '/:id', '', 'GET', NULL, 0, 1, 0, 0),
  (26, 'core', '', '/core/config/add', '/core/config/lists', '增加配置', '增加系统配置', 'admin', 2, 'form', 'v1', '', '', 'GET|POST', NULL, 0, 1, 0, 0),
  (27, 'core', '', '/core/config/edit', '/core/config/lists', '修改配置', '修改系统配置', 'admin', 2, 'form', 'v1', '/:id', '', 'GET|PUT', NULL, 0, 1, 0, 0),
  (28, 'core', '', '/core/config/saveBatch', '/core', '系统设置', '批量修改系统配置的值', 'admin', 1, 'form', 'v1', '/:module', '/core', 'GET|PUT', NULL, 0, 1, 1, 0),
  (29, 'core', '', '/core/user/login', '', '用户登录', '用户登录', 'home', 3, '', 'v1', '', '', 'GET|POST', '{\"POST\":{\"description\":\"使用凭证登录系统\",\"params\":[{\"description\":\"支持多种身份验证方式\",\"require\":\"0\",\"name\":\"identity_type\",\"title\":\"验证方式\",\"example\":\"0\"},{\"require\":\"1\",\"name\":\"identifier\",\"title\":\"账号\",\"description\":\"用户名，邮箱，手机号\",\"example\":\"admin@qq.com\"},{\"name\":\"credential\",\"require\":\"1\",\"title\":\"凭证\",\"description\":\"密码\",\"example\":\"qwe123\"}],\"divider\":\"\"}}', 0, 1, 0, 0),
  (30, 'core', '', '/core/api/add', '/core/api/trees', '添加前台API', '添加前台API接口', 'admin', 2, 'form', 'v1', '', '', 'GET|POST', NULL, 0, 1, 0, 0),
  (31, 'core', '', '/core/api/delete', '/core/api/trees', '删除前台API', '删除后前台API接口', 'admin', 2, '', 'v1', '/:id', '', 'DELETE', NULL, 0, 1, 0, 0),
  (32, 'core', '', '/core/api/edit', '/core/api/trees', '修改前台API', '修改前台API接口', 'admin', 2, 'form', 'v1', '/:id', '', 'GET|PUT', NULL, 0, 1, 0, 0),
  (33, 'core', '', '/core/api/trees', '/developer', 'API管理', '管理前台API', 'admin', 1, 'list', 'v1', '', '', 'GET', NULL, 0, 1, 3, 0),
  (36, 'core', '', '/core/user/isLogin', '', '检查登录状态', '检查用户是否真的登录', 'home', 3, 'route', 'v1', '', '', 'GET', NULL, 0, 1, 0, 0),
  (37, 'core', '', '/core/user/info', '', '获取用户信息', '获取登录的用户信息', 'home', 3, 'route', 'v1', '', '', 'GET', NULL, 0, 1, 0, 0),
  (38, 'core', '', '/core/api/doc', '/core/api/trees', '修改API文档', '修改API接口文档', 'admin', 2, 'form', 'v1', '/:id', '', 'GET|PUT', NULL, 0, 1, 0, 0),
  (39, 'core', '', '/core/user/logout', '', '注销登录', '', 'home', 3, 'route', 'v1', '', '', 'DELETE', NULL, 0, 1, 0, 0),
  (40, 'core', '', '/core/safety/resetPassword', '', '重置密码', '', 'home', 3, 'route', 'v1', '', '', 'PUT', NULL, 0, 1, 0, 0),
  (41, 'cms', 'ios-create', '/cms', '', '内容', '', 'admin', 0, '', '', '', '', '', NULL, 0, 1, 2, 0),
  (42, 'cms', '', '/cms/cate/trees', '/cms', '分类管理', '', 'admin', 1, 'list', 'v1', '', '', 'GET', NULL, 0, 1, 0, 0),
  (43, 'cms', '', '/cms/post/lists', '/cms/cate/trees', '文章管理', '', 'admin', 2, 'list', 'v1', '/:cate_id', '', 'GET', NULL, 0, 1, 0, 0),
  (44, 'cms', '', '/cms/cate/add', '/cms/cate/trees', '添加分类', '', 'admin', 2, 'form', 'v1', '', '', 'POST|GET', NULL, 0, 1, 0, 0),
  (45, 'cms', '', '/cms/cate/edit', '/cms/cate/trees', '修改分类', '', 'admin', 2, 'form', 'v1', '/:id', '', 'PUT|GET', NULL, 0, 1, 0, 0),
  (46, 'cms', '', '/cms/post/add', '/cms/post/lists', '添加文章', '', 'admin', 2, 'form', 'v1', '/:cid', '', 'POST|GET', NULL, 0, 1, 0, 0),
  (47, 'cms', '', '/cms/post/edit', '/cms/post/lists', '修改文章', '', 'admin', 2, 'form', 'v1', '/:id', '', 'PUT|GET', NULL, 0, 1, 0, 0),
  (48, 'cms', '', '/cms/post/lists', '', '文章列表', '', 'home', 3, 'route', 'v1', '/:cid', '', 'GET', NULL, 0, 1, 51, 0),
  (49, 'cms', '', '/cms/post/info', '', '文章详情', '', 'home', 3, 'route', 'v1', '/:id', '', 'GET', NULL, 0, 1, 51, 0),
  (50, 'cms', '', '/cms/post/my', '', '我的文章', '', 'home', 3, 'route', 'v1', '', '', 'GET', NULL, 0, 1, 51, 0),
  (52, 'core', '', '/core/user/login', '', '后台登录', '后台登录', 'admin', 3, '', 'v1', '', '', 'GET|POST', '{\"POST\":{\"description\":\"使用凭证登录系统\",\"params\":[{\"description\":\"支持多种身份验证方式\",\"require\":\"0\",\"name\":\"identity_type\",\"title\":\"验证方式\",\"example\":\"0\"},{\"require\":\"1\",\"name\":\"identifier\",\"title\":\"账号\",\"description\":\"用户名，邮箱，手机号\",\"example\":\"admin@qq.com\"},{\"name\":\"credential\",\"require\":\"1\",\"title\":\"凭证\",\"description\":\"密码\",\"example\":\"qwe123\"}],\"divider\":\"\"}}', 0, 1, 0, 0),
  (53, 'core', '', '/core/install/step1', '', '安装步骤1', '', 'home', 3, '', 'v1', '', '', 'GET|POST', '', 0, 1, 0, 0),
  (54, 'core', '', '/core/install/step2', '', '安装步骤2', '', 'home', 3, '', 'v1', '', '', 'GET|POST', '', 0, 1, 0, 0),
  (55, 'core', '', '/core/install/step3', '', '安装步骤3', '', 'home', 3, '', 'v1', '', '', 'GET|POST', '', 0, 1, 0, 0),
  (56, 'core', '', '/core/install/step4', '', '安装步骤4', '', 'home', 3, '', 'v1', '', '', 'GET|POST', '', 0, 1, 0, 0),
  (57, 'core', '', '/core/install/step5', '', '安装步骤5', '', 'home', 3, '', 'v1', '', '', 'GET|POST', '', 0, 1, 0, 0),
  (58, 'core', '', '/core/index/config', '/core', '核心配置', '获取核心模块配置', 'home', 3, '', 'v1', '', '', 'GET', NULL, 0, 1, 0, 0),
  (59, 'core', '', '/core/index/index', '', '后台首页', '', 'admin', 3, 'route', 'v1', '', '', 'GET', NULL, 0, 1, 0, 0),
  (60, 'core', '', '/core/index/setStatus', '/core', '设置状态', '设置状态', 'admin', 3, '', 'v1', '', '', 'PUT', NULL, 0, 1, 0, 0),
  (61, 'core', '', '/core/module/import', '/core/module/lists', '导入模块', '导入模块信息', 'admin', 2, 'from', 'v1', '/:name', '', 'POST', NULL, 0, 1, 0, 0);


CREATE TABLE `ia_core_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户UID',
  `key` varchar(256) NOT NULL DEFAULT '' COMMENT '密钥',
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

INSERT INTO `ia_core_user` (`id`, `key`, `nickname`, `username`, `password`, `avatar`, `extend_info`, `status`, `roles`, `register_time`, `delete_time`)
VALUES
	(1, 'initadmin-auth-key', '超级管理员', 'initadmin', '59628980c728862eeb861783c9499c14', 'https://en.gravatar.com/userimage/48080041/ee1136a1e9cc9a1cac548bfcb7edb43f.jpeg', NULL, 1, 'super_admin,admin', 0, 0);


CREATE TABLE `ia_core_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级',
  `name` varchar(255) NOT NULL COMMENT '角色名称',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '角色标题',
  `admin_auth` longtext COMMENT '后台权限',
  `api_auth` longtext COMMENT '接口权限',
  `sortnum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色表';

INSERT INTO `ia_core_role` (`id`, `pid`, `name`, `title`, `admin_auth`, `api_auth`, `sortnum`, `status`, `delete_time`)
VALUES
	(1, 0, 'super_admin', '超级管理员', '', '', 0, 1, 0),
	(2, 1, 'admin', '管理员', '/v1/admin/core/role/trees,/v1/admin/core/role/edit,/v1/admin/core/role/delete,/v1/admin/core/role/add,/v1/admin/core/user/lists,/v1/admin/core/user/edit,/v1/admin/core/user/delete,/v1/admin/core/user/add,/v1/admin/core/config/lists', '', 0, 1, 0),
	(3, 2, 'operation', '运营部', '', NULL, 0, 1, 0);


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


CREATE TABLE `ia_core_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `module` varchar(255) NOT NULL DEFAULT '' COMMENT '模块名称',
  `config_cate` varchar(64) NOT NULL DEFAULT '' COMMENT '配置分组',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '配置名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '配置标题',
  `config_type` varchar(16) NOT NULL DEFAULT '' COMMENT '配置类型',
  `value` text NOT NULL COMMENT '配置值',
  `placeholder` varchar(100) NOT NULL DEFAULT '' COMMENT '说明',
  `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `options` varchar(256) NOT NULL DEFAULT '' COMMENT '配置额外值',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统配置',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开发模式才显示',
  `sortnum` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置表';

INSERT INTO `ia_core_config` (`module`, `config_cate`, `name`, `title`, `config_type`, `value`, `placeholder`, `tip`, `options`, `is_system`, `is_dev`, `sortnum`, `status`, `delete_time`)
VALUES
  ('core', 99, 'config_cate', '配置分组', 'array', '1:系统\n3:SEO\n99:其它', '请输入配置分组信息', '请输入配置分组信息', '', 1, 0, 7, 1, 0),
  ('core', 1, 'title', '项目名称', 'text', 'InitAdmin', '请输入项目名称', '请输入项目名称', '', 1, 0, 1, 1, 0),
  ('core', 1, 'logo', '项目logo', 'image', 'https://i.loli.net/2019/09/11/xZTmQFuJifzPpc3.png', '请上传系统logo', '请上传系统logo', '', 1, 0, 3, 1, 0),
  ('core', 1, 'favicon', 'ICO图标', 'image', '', '请上传ICO图标', '请上传ICO图标', '', 1, 0, 4, 1, 0),
  ('core', 1, 'slogan', '项目口号', 'text', '渐进式模块化后台', '请输入您的项目口号', '请输入您的项目口号', '', 1, 0, 2, 1, 0),
  ('core', 1, 'copyright', '版权信息', 'text', 'Copyright © initadmin.net  All rights reserved.', '请输入您的版权信息', '请输入您的版权信息', '', 1, 0, 5, 1, 0),
  ('core', 1, 'icp', '备案号', 'text', '苏ICP备15020094号', '请输入您的域名备案号', '请输入您的域名备案号', '', 1, 0, 6, 1, 0),
  ('core', 1, 'theme', '主题', 'text', 'default', '主题', '主题', '', 1, 0, 6, 1, 0);


CREATE TABLE `ia_core_login` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT 'UID',
  `key` varchar(256) NOT NULL DEFAULT '' COMMENT '加密key',
  `token` text NOT NULL COMMENT '登录token',
  `refresh_token` text COMMENT '刷新token',
  `login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录时间',
  `expire_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `client_type` int(3) NOT NULL DEFAULT '0' COMMENT '客户端类型1pc2wap3app',
  `client_name` varchar(128) NOT NULL DEFAULT '' COMMENT '设备名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录信息表';


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



INSERT INTO `ia_core_module` (`name`, `title`, `description`, `developer`, `website`, `version`, `build`, `status`, `sortnum`, `delete_time`)
VALUES
  ('cms', '内容', '内容模块为通用的CMS文章博客模块', 'jry', 'https://jiangruyi.com', '0.1.0', 'beta1_20190508', 1, 2, 0);

CREATE TABLE `ia_cms_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类',
  `cate_type` int(11) NOT NULL DEFAULT '0' COMMENT '分类类型0列表1单页2路由',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(128) NOT NULL DEFAULT '' COMMENT '简介',
  `cover` varchar(256) NOT NULL DEFAULT '' COMMENT '封面图',
  `banner` varchar(256) NOT NULL DEFAULT '' COMMENT 'Banner图',
  `content` longtext COMMENT '单页类型时的内容',
  `sortnum` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(3) NOT NULL DEFAULT '0' COMMENT '状态1正常0禁用',
  `is_hide` int(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='CMS分类表';

INSERT INTO `ia_cms_cate` (`id`, `pid`, `cate_type`, `title`, `description`, `cover`, `banner`, `content`, `sortnum`, `status`, `is_hide`, `create_time`, `delete_time`)
VALUES
  (2,0,0,'产品服务','','','',NULL,0,1,0,0,0),
  (3,0,0,'客户案例','','','',NULL,0,1,0,0,0),
  (4,0,0,'新闻动态','','','',NULL,0,1,0,0,0),
  (5,0,0,'关于我们','','','',NULL,0,1,0,0,0);

CREATE TABLE `ia_cms_post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '发布ID',
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `cid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `description` varchar(128) NOT NULL DEFAULT '' COMMENT '简介',
  `content` longtext NOT NULL COMMENT '内容',
  `cover` varchar(256) NOT NULL DEFAULT '' COMMENT '封面图',
  `post_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数量',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '回复数量',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态1禁用1正常',
  `review_status` int(1) NOT NULL DEFAULT '1' COMMENT '审核状态0待审核1通过-1拒绝',
  `delete_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章发布表';

INSERT INTO `ia_cms_post` (`id`, `uid`, `cid`, `title`, `description`, `content`, `cover`, `post_time`, `view_count`, `comment_count`, `status`, `review_status`, `delete_time`)
VALUES
  (3,0,4,'InitAdmin正式发布','InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能','<p><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能，后期上线模块商城将打造类似composer、npm的开放式插件市场；同时我们将打造一套兼容性的API标准，从ThinkPHP5.1+Vue2开始，逐步覆盖larval、spring-boot、django、yii、koa、react等多语言框架。</span><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">官方网站：&nbsp;</span><a style=\"color: #72b939; text-decoration-line: none; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" href=\"https://initadmin.net/\">https://initadmin.net</a></p>','http://localhost/initadmin/public/static/cms.png',1557205908,0,0,1,1,0),
  (4,0,4,'InitAdmin正式发布','InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能','<p><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能，后期上线模块商城将打造类似composer、npm的开放式插件市场；同时我们将打造一套兼容性的API标准，从ThinkPHP5.1+Vue2开始，逐步覆盖larval、spring-boot、django、yii、koa、react等多语言框架。</span><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">官方网站：&nbsp;</span><a style=\"color: #72b939; text-decoration-line: none; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" href=\"https://initadmin.net/\">https://initadmin.net</a></p>','http://localhost/initadmin/public/static/cms.png',1557205908,0,0,1,1,0),
  (5,0,4,'InitAdmin正式发布','InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能','<p><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能，后期上线模块商城将打造类似composer、npm的开放式插件市场；同时我们将打造一套兼容性的API标准，从ThinkPHP5.1+Vue2开始，逐步覆盖larval、spring-boot、django、yii、koa、react等多语言框架。</span><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">官方网站：&nbsp;</span><a style=\"color: #72b939; text-decoration-line: none; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" href=\"https://initadmin.net/\">https://initadmin.net</a></p>','http://localhost/initadmin/public/static/cms.png',1557205908,0,0,1,1,0),
  (6,0,4,'InitAdmin正式发布','InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能','<p><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能，后期上线模块商城将打造类似composer、npm的开放式插件市场；同时我们将打造一套兼容性的API标准，从ThinkPHP5.1+Vue2开始，逐步覆盖larval、spring-boot、django、yii、koa、react等多语言框架。</span><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">官方网站：&nbsp;</span><a style=\"color: #72b939; text-decoration-line: none; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" href=\"https://initadmin.net/\">https://initadmin.net</a></p>','http://localhost/initadmin/public/static/cms.png',1557205908,0,0,1,1,0),
  (7,0,4,'InitAdmin正式发布','InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能','<p><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能，后期上线模块商城将打造类似composer、npm的开放式插件市场；同时我们将打造一套兼容性的API标准，从ThinkPHP5.1+Vue2开始，逐步覆盖larval、spring-boot、django、yii、koa、react等多语言框架。</span><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">官方网站：&nbsp;</span><a style=\"color: #72b939; text-decoration-line: none; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" href=\"https://initadmin.net/\">https://initadmin.net</a></p>','http://localhost/initadmin/public/static/cms.png',1557205908,0,0,1,1,0),
  (8,0,4,'InitAdmin正式发布','InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能','<p><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能，后期上线模块商城将打造类似composer、npm的开放式插件市场；同时我们将打造一套兼容性的API标准，从ThinkPHP5.1+Vue2开始，逐步覆盖larval、spring-boot、django、yii、koa、react等多语言框架。</span><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">官方网站：&nbsp;</span><a style=\"color: #72b939; text-decoration-line: none; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" href=\"https://initadmin.net/\">https://initadmin.net</a></p>','http://localhost/initadmin/public/static/cms.png',1557205908,0,0,1,1,0),
  (9,0,4,'InitAdmin正式发布','InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能','<p><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">InitAdmin是一套渐进式模块化开源后台(目前基于ThinkPHP5.1+Vue2)，采用前后端分离技术，数据交互采用json格式，功能低耦合高内聚；核心模块支持系统设置、权限管理、用户管理、菜单管理、API管理等功能，后期上线模块商城将打造类似composer、npm的开放式插件市场；同时我们将打造一套兼容性的API标准，从ThinkPHP5.1+Vue2开始，逐步覆盖larval、spring-boot、django、yii、koa、react等多语言框架。</span><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><br style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" /><span style=\"color: #323232; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\">官方网站：&nbsp;</span><a style=\"color: #72b939; text-decoration-line: none; font-family: \'Century Gothic\', \'Microsoft yahei\'; font-size: 16px; background-color: #ffffff;\" href=\"https://initadmin.net/\">https://initadmin.net</a></p>','http://localhost/initadmin/public/static/cms.png',1557205908,0,0,1,1,0);


INSERT INTO `ia_core_menu` (`module`, `icon`, `path`, `pmenu`, `title`, `tip`, `menu_type`, `route_type`, `api_prefix`, `api_suffix`, `api_params`, `api_method`, `doc`, `is_hide`, `sortnum`, `delete_time`)
VALUES
  ('cms', 'ios-create', '/cms', '', '内容', '', 0, '', '', '', '', '', NULL, 0, 2, 0),
  ('cms', '', '/cms/cate/trees', '/cms', '分类管理', '', 1, 'list', 'v1', '', '', 'GET', NULL, 0, 0, 0),
  ('cms', '', '/cms/post/lists', '/cms/cate/trees', '文章管理', '', 2, 'list', 'v1', '/:cid', '', 'GET', NULL, 0, 0, 0),
  ('cms', '', '/cms/cate/add', '/cms/cate/trees', '添加分类', '', 2, 'form', 'v1', '', '', 'POST|GET', NULL, 0, 0, 0),
  ('cms', '', '/cms/cate/edit', '/cms/cate/trees', '修改分类', '', 2, 'form', 'v1', '/:id', '', 'PUT|GET', NULL, 0, 0, 0),
  ('cms', '', '/cms/post/add', '/cms/post/lists', '添加文章', '', 2, 'form', 'v1', '/:cid', '', 'POST|GET', NULL, 0, 0, 0),
  ('cms', '', '/cms/post/edit', '/cms/post/lists', '修改文章', '', 2, 'form', 'v1', '/:id', '', 'PUT|GET', NULL, 0, 0, 0),
  ('cms', '', '/cms/post/lists', '', '文章列表', '', 5, 'route', 'v1', '/:cid', '', 'GET', NULL, 0, 51, 0),
  ('cms', '', '/cms/post/info', '', '文章详情', '', 5, 'route', 'v1', '/:id', '', 'GET', NULL, 0, 51, 0),
  ('cms', '', '/cms/post/my', '', '我的文章', '', 5, 'route', 'v1', '', '', 'GET', NULL, 0, 51, 0);

