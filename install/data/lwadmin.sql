CREATE TABLE `#__admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `loginname` varchar(50) DEFAULT NULL COMMENT '登录名',
  `username` varchar(50) DEFAULT NULL COMMENT '管理员名称',
  `security_code` varchar(20) DEFAULT NULL COMMENT '安全码',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `record_time` int(11) DEFAULT NULL COMMENT '记录时间',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '管理员表';

INSERT INTO `#__admin` VALUES ('1', '===adminName===', '===adminName===', '===securityCode===', '===password===', '1', '===record_timestamp===', '1');

CREATE TABLE `#__menu` (
  `id` int(255) NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `name` varchar(255) DEFAULT NULL COMMENT '菜单名称',
  `icon` varchar(255) DEFAULT 'icon-list' COMMENT '菜单图标',
  `sort` int(255) DEFAULT NULL COMMENT '排序',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  `record_time` int(11) DEFAULT NULL COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '菜单表';

INSERT INTO `#__menu` VALUES ('1', '基础操作', 'fa-list', '1', '1', null);
INSERT INTO `#__menu` VALUES ('2', '系统业务', 'fa-list', '2', '1', null);
INSERT INTO `#__menu` VALUES ('3', '系统设置', 'fa-list', '3', '1', null);

CREATE TABLE `#__method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `describe` varchar(255) DEFAULT NULL,
  `is_menu` tinyint(1) DEFAULT '0',
  `is_default` tinyint(1) DEFAULT '0',
  `sort` int(20) DEFAULT NULL,
  `params` varchar(255) DEFAULT NULL COMMENT '参数',
  `is_log` tinyint(1) DEFAULT '0',
  `status` int(11) DEFAULT '1' COMMENT '1: 正常 -99: 删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '控制器方法表';

INSERT INTO `#__method` VALUES (1, '0', 'method', '方法控制器', 0, 0, 1, NULL, 1, 1);
INSERT INTO `#__method` VALUES (2, '1', 'index', '主页', 1, 0, 1, '', 0, 1);
INSERT INTO `#__method` VALUES (3, '1', 'lists', '列表', 0, 0, 2, '', 0, 1);
INSERT INTO `#__method` VALUES (4, '1', 'add', '添加', 0, 0, 3, '', 0, 1);
INSERT INTO `#__method` VALUES (5, '1', 'edit', '编辑', 0, 0, 4, '', 0, 1);
INSERT INTO `#__method` VALUES (6, '1', 'del', '删除', 0, 0, 5, '', 0, 1);
INSERT INTO `#__method` VALUES (7, '1', 'bindmenuindex', '方法菜单主页', 0, 0, 6, '', 0, 1);
INSERT INTO `#__method` VALUES (8, '1', 'bindmenuadd', '方法菜单添加', 0, 0, 7, '', 0, 1);
INSERT INTO `#__method` VALUES (9, '1', 'bindmenuedit', '方法菜单编辑', 0, 0, 8, '', 0, 1);
INSERT INTO `#__method` VALUES (10, '1', 'bindmenulist', '方法菜单列表', 0, 0, 9, '', 0, 1);
INSERT INTO `#__method` VALUES (11, '1', 'bindmenudel', '方法菜单删除', 0, 0, 10, '', 0, 1);
INSERT INTO `#__method` VALUES (12, '0', 'admin', '管理员', 0, 0, 2, NULL, 1, 1);
INSERT INTO `#__method` VALUES (13, '12', 'index', '主页', 1, 0, 1, '', 0, 1);
INSERT INTO `#__method` VALUES (14, '12', 'lists', '列表', 0, 0, 2, '', 0, 1);
INSERT INTO `#__method` VALUES (15, '12', 'add', '添加', 0, 0, 3, '', 0, 1);
INSERT INTO `#__method` VALUES (16, '12', 'edit', '编辑', 0, 0, 4, '', 0, 1);
INSERT INTO `#__method` VALUES (17, '12', 'del', '删除', 0, 0, 5, '', 0, 1);
INSERT INTO `#__method` VALUES (18, '0', 'menu', '菜单', 0, 0, 3, NULL, 0, 1);
INSERT INTO `#__method` VALUES (19, '18', 'index', '主页', 1, 0, 1, '', 0, 1);
INSERT INTO `#__method` VALUES (20, '18', 'lists', '列表', 0, 0, 2, '', 0, 1);
INSERT INTO `#__method` VALUES (21, '18', 'add', '添加', 0, 0, 3, '', 0, 1);
INSERT INTO `#__method` VALUES (22, '18', 'edit', '编辑', 0, 0, 4, '', 0, 1);
INSERT INTO `#__method` VALUES (23, '18', 'del', '删除', 0, 0, 5, '', 0, 1);
INSERT INTO `#__method` VALUES (24, '0', 'role', '角色', 0, 0, 4, NULL, 1, 1);
INSERT INTO `#__method` VALUES (25, '24', 'index', '主页', 1, 0, 1, '', 0, 1);
INSERT INTO `#__method` VALUES (26, '24', 'lists', '列表', 0, 0, 2, '', 0, 1);
INSERT INTO `#__method` VALUES (27, '24', 'add', '添加', 0, 0, 3, '', 0, 1);
INSERT INTO `#__method` VALUES (28, '24', 'edit', '编辑', 0, 0, 4, '', 0, 1);
INSERT INTO `#__method` VALUES (29, '24', 'del', '删除', 0, 0, 5, '', 0, 1);
INSERT INTO `#__method` VALUES (30, '24', 'getrolepowerdata', '获取权限数据', 0, 0, 6, '', 0, 1);
INSERT INTO `#__method` VALUES (31, '24', 'setpower', '配权', 0, 0, 7, '', 0, 1);
INSERT INTO `#__method` VALUES (32, '0', 'log', '日志', 0, 1, 5, NULL, 0, 1);
INSERT INTO `#__method` VALUES (33, '32', 'index', '主页', 1, 0, 1, '', 0, 1);


CREATE TABLE `#__method_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `method_id` int(11) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '记录次绑定 controller 的 menu  名称',
  `param` varchar(255) DEFAULT NULL COMMENT '可能存在参数',
  `sort` int(255) DEFAULT NULL COMMENT '排序',
  `status` int(255) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '方法绑定菜单表';

INSERT INTO `#__method_menu` VALUES (1, 2, 3, '控制器管理', '', 1, 1);
INSERT INTO `#__method_menu` VALUES (2, 13, 2, '管理员管理', '', 1, 1);
INSERT INTO `#__method_menu` VALUES (3, 19, 3, '菜单管理', '', 1, 1);
INSERT INTO `#__method_menu` VALUES (4, 25, 3, '角色管理', '', 1, 1);
INSERT INTO `#__method_menu` VALUES (5, 33, 3, '日志管理', '', 1, 1);

CREATE TABLE `#__role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT '1',
  `record_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '角色表';

INSERT INTO `#__role` VALUES ('1', '0', '最高管理员', '1', null);
INSERT INTO `#__role` VALUES ('2', '1', '后台管理员', '1', null);

CREATE TABLE `#__role_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `method_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '角色权限表';

CREATE TABLE `#__sys_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL,
  `admin_name` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `controller_id` int(11) DEFAULT NULL,
  `controller_name` varchar(255) DEFAULT NULL,
  `controller_describe` varchar(255) DEFAULT NULL,
  `method_id` int(11) DEFAULT NULL,
  `method_name` varchar(255) DEFAULT NULL,
  `method_describe` varchar(255) DEFAULT NULL,
  `get_params` varchar(255) DEFAULT NULL,
  `post_params` varchar(255) DEFAULT NULL,
  `record_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT '系统日志表';

