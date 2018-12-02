CREATE TABLE `tpvue_core_user_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户UID',
  `nickname` varchar(63) NOT NULL COMMENT '用户昵称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像地址',
  `age` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户年龄',
  `country` varchar(8) NOT NULL DEFAULT '' COMMENT '国家代码,如+86',
  `city` varchar(255) NOT NULL COMMENT '常驻城市可用于判断异地登录',
  `register_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户注册时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态,1正常,0待审核,-1删除,-2禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户帐号信息表';

INSERT INTO `tpvue_core_user_info` (`id`, `nickname`, `avatar`, `age`, `country`, `city`, `register_time`, `status`)
VALUES
  (1, '超级管理员', '1', 8, '86', '江苏,南京,鼓楼区', 0, 1);


CREATE TABLE `tpvue_core_user_identity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT 'UID',
  `verified` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已验证',
  `identity_type` varchar(11) NOT NULL DEFAULT '' COMMENT '账号类型',
  `identifier` varchar(127) NOT NULL COMMENT '帐号',
  `credential` varchar(255) NOT NULL COMMENT '密码',
  `is_oauth2` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否OAuth2登录',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态:1正常0禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登陆凭证信息表';

INSERT INTO `tpvue_core_user_identity` (`id`, `uid`, `verified`, `identity_type`, `identifier`, `credential`, `is_oauth2`, `create_time`, `status`)
VALUES
  (1, 1, 1, '1', 'admin', 'admin', 0, 0, 1);

  CREATE TABLE `tpvue_core_user_log` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户账户日志记录';
