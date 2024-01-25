use `{database}`;
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for gz_account
-- ----------------------------
DROP TABLE IF EXISTS `gz_account`;
CREATE TABLE `gz_account` (
  `acid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `key` varchar(16) NOT NULL COMMENT '账户标识',
  `name` varchar(6) NOT NULL DEFAULT '仅做提示用' COMMENT '账户名称',
  `alipay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付宝开关',
  `wechat` tinyint(1) NOT NULL DEFAULT '0' COMMENT '微信支付开关',
  `update_time` int(12) NOT NULL COMMENT '配置时间',
  `sort` int(12) NOT NULL DEFAULT '10' COMMENT '类型排序',
  `data` text COMMENT '配置数据',
  `pc` tinyint(1) NOT NULL DEFAULT '0' COMMENT '电脑网页开关',
  `wap` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机网页开关',
  `on` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开关',
  `config` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已配置',
  PRIMARY KEY (`acid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_account
-- ----------------------------
INSERT INTO `gz_account` VALUES ('1', 'alipay', '支付宝官方', '1', '0', '1645964986', '0', 'a:3:{s:5:\"appid\";s:0:\"\";s:6:\"pubkey\";s:0:\"\";s:7:\"private\";s:0:\"\";}', '0', '0', '0', '0');
INSERT INTO `gz_account` VALUES ('2', 'wechat', '微信支付官方', '0', '1', '1645965007', '2', 'a:3:{s:5:\"appid\";s:0:\"\";s:3:\"key\";s:0:\"\";s:10:\"merchantid\";s:0:\"\";}', '0', '0', '0', '0');
INSERT INTO `gz_account` VALUES ('3', 'epay', '易支付', '1', '1', '0', '4', null, '0', '0', '0', '1');
INSERT INTO `gz_account` VALUES ('4', 'codepay', '码支付', '0', '0', '1645521097', '2', 'a:2:{s:5:\"merid\";s:0:\"\";s:3:\"key\";s:0:\"\";}', '0', '0', '0', '0');
INSERT INTO `gz_account` VALUES ('5', 'alipaycode', '支付宝当面付', '1', '0', '1645964999', '1', 'a:3:{s:5:\"appid\";s:0:\"\";s:6:\"pubkey\";s:0:\"\";s:7:\"private\";s:0:\"\";}', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for gz_admin
-- ----------------------------
DROP TABLE IF EXISTS `gz_admin`;
CREATE TABLE `gz_admin` (
  `aid` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `salt` char(4) NOT NULL COMMENT '密码盐',
  `top` tinyint(1) NOT NULL COMMENT '是否最高',
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gz_card
-- ----------------------------
DROP TABLE IF EXISTS `gz_card`;
CREATE TABLE `gz_card` (
  `cid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gid` int(12) NOT NULL COMMENT '商品ID',
  `gpid` int(12) NOT NULL COMMENT '规格ID',
  `card` varchar(255) NOT NULL COMMENT '卡号',
  `pwd` varchar(255) DEFAULT NULL COMMENT '密码',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '卡密状态',
  `num` int(12) NOT NULL DEFAULT '0' COMMENT '已租出次数',
  `sale_time` int(12) DEFAULT NULL COMMENT '售出时间',
  `oid` int(12) DEFAULT NULL COMMENT '订单ID',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gz_config
-- ----------------------------
DROP TABLE IF EXISTS `gz_config`;
CREATE TABLE `gz_config` (
  `coid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `key` varchar(32) NOT NULL COMMENT '配置项',
  `value` text NOT NULL COMMENT '配置值',
  `dec` varchar(100) DEFAULT NULL COMMENT '释义',
  PRIMARY KEY (`coid`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_config
-- ----------------------------
INSERT INTO `gz_config` VALUES ('1', 'webconfig', 'a:8:{s:5:\"title\";s:28:\"格子吧专业发卡系统s\";s:6:\"slogan\";s:25:\"最专业的发卡软件s\";s:7:\"keyword\";s:9:\"关键词\";s:4:\"desc\";s:16:\"发卡,格子吧\";s:5:\"beian\";s:9:\"备案号\";s:4:\"logo\";s:58:\"/upload/logo/20220301\\ebd37193c527658373509dbe8bb47c15.png\";s:7:\"company\";s:18:\"格子吧工作室\";s:6:\"static\";s:0:\"\";}', '网站信息配置');
INSERT INTO `gz_config` VALUES ('18', 'qqlogin', 'a:3:{s:2:\"on\";s:1:\"1\";s:8:\"clientid\";s:0:\"\";s:6:\"secret\";s:0:\"\";}', null);
INSERT INTO `gz_config` VALUES ('8', 'webOn', 'a:3:{s:5:\"youke\";s:1:\"1\";s:3:\"reg\";s:1:\"1\";s:4:\"user\";s:1:\"1\";}', null);
INSERT INTO `gz_config` VALUES ('9', 'order', 'a:7:{s:4:\"name\";s:0:\"\";s:3:\"usr\";s:1:\"1\";s:3:\"pwd\";s:1:\"1\";s:6:\"usrtip\";s:0:\"\";s:6:\"pwdtip\";s:0:\"\";s:3:\"sms\";s:1:\"0\";s:5:\"email\";s:1:\"0\";}', null);
INSERT INTO `gz_config` VALUES ('10', 'email', 'a:6:{s:4:\"smtp\";s:0:\"\";s:3:\"pwd\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"admin\";s:1:\"1\";s:5:\"order\";s:1:\"0\";}', null);
INSERT INTO `gz_config` VALUES ('11', 'indexwindows', '', null);
INSERT INTO `gz_config` VALUES ('12', 'goodswindows', '', null);
INSERT INTO `gz_config` VALUES ('13', 'notice', '<p>ssss</p>', null);
INSERT INTO `gz_config` VALUES ('14', 'admin', 'a:3:{s:5:\"email\";s:0:\"\";s:2:\"qq\";s:0:\"\";s:3:\"tel\";s:0:\"\";}', null);
INSERT INTO `gz_config` VALUES ('15', 'orderemail', '<p>您好，您的宝贝已发货</p>', null);
INSERT INTO `gz_config` VALUES ('16', 'orderemailsubject', ' 订单邮件', null);
INSERT INTO `gz_config` VALUES ('17', 'token', 'IhI2xZAvOa0qP2vEw+0HRYEzqjorhvAOqa38JP/S4OFAgLwA6XeF6TkBufTtfMe1', null);

-- ----------------------------
-- Table structure for gz_goods
-- ----------------------------
DROP TABLE IF EXISTS `gz_goods`;
CREATE TABLE `gz_goods` (
  `gid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gtid` int(12) NOT NULL DEFAULT '0' COMMENT '所属分类',
  `name` varchar(128) NOT NULL COMMENT '商品名称',
  `dec` text NOT NULL COMMENT '商品说明',
  `details` text NOT NULL COMMENT '商品详情',
  `is_pwd` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要密码',
  `is_login` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要登录',
  `top` int(12) NOT NULL DEFAULT '-1' COMMENT '商品限购数',
  `add_time` int(12) NOT NULL COMMENT '添加时间',
  `update_time` int(12) NOT NULL COMMENT '上次修改时间',
  `sale` int(12) NOT NULL DEFAULT '0' COMMENT '销量',
  `img` varchar(255) DEFAULT NULL COMMENT '商品主图',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '商品类型',
  `num` int(12) NOT NULL DEFAULT '1' COMMENT '限租次数',
  `is_head` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否手动发货商品',
  `is_sale` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `price` decimal(10,2) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '10',
  `delete_time` int(11) NOT NULL DEFAULT '0',
  `real_sale` int(11) NOT NULL DEFAULT '0' COMMENT '真实销量',
  `other` int(11) NOT NULL DEFAULT '0' COMMENT '附加选项',
  `pwd` varchar(32) DEFAULT NULL,
  `window` text COMMENT '商品弹窗',
  `short` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gz_goods_price
-- ----------------------------
DROP TABLE IF EXISTS `gz_goods_price`;
CREATE TABLE `gz_goods_price` (
  `gpid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gid` int(12) NOT NULL COMMENT '商品ID',
  `gsid` int(12) NOT NULL COMMENT '规格ID',
  `ugid` int(12) NOT NULL DEFAULT '0' COMMENT '用户组ID(0为普通用户)',
  `price` decimal(12,2) DEFAULT NULL COMMENT '原价',
  `sale_price` decimal(12,2) NOT NULL COMMENT '售价',
  `cost` decimal(12,3) DEFAULT NULL COMMENT '成本价',
  PRIMARY KEY (`gpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gz_goods_specs
-- ----------------------------
DROP TABLE IF EXISTS `gz_goods_specs`;
CREATE TABLE `gz_goods_specs` (
  `gsid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gid` int(12) NOT NULL COMMENT '商品ID',
  `is_more` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认规格',
  `name` varchar(60) NOT NULL COMMENT '规格名称',
  `sort` int(8) NOT NULL DEFAULT '10' COMMENT '规格排序',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `from_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`gsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gz_goods_type
-- ----------------------------
DROP TABLE IF EXISTS `gz_goods_type`;
CREATE TABLE `gz_goods_type` (
  `gtid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(64) NOT NULL COMMENT '分类名称',
  `sort` int(8) NOT NULL COMMENT '分类排序',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  `dec` text COMMENT '分类说明',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `goods_sort` tinyint(4) NOT NULL DEFAULT '0' COMMENT '商品排序方式',
  `delete_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gtid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_goods_type
-- ----------------------------
INSERT INTO `gz_goods_type` VALUES ('2', '分类', '9', '1600000000', '/uploads/goodstype/20211104213408negakpej.png', '1', '1', '0', '0');

-- ----------------------------
-- Table structure for gz_log
-- ----------------------------
DROP TABLE IF EXISTS `gz_log`;
CREATE TABLE `gz_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `add_time` int(12) NOT NULL COMMENT '访问时间',
  `ip` varchar(128) NOT NULL COMMENT '来自IP',
  `browser` text NOT NULL COMMENT '浏览器信息',
  `request` text,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gz_menu
-- ----------------------------
DROP TABLE IF EXISTS `gz_menu`;
CREATE TABLE `gz_menu` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(12) NOT NULL COMMENT '菜单',
  `sort` int(5) NOT NULL DEFAULT '10' COMMENT '排序',
  `url` text NOT NULL COMMENT '链接',
  `is_head` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_menu
-- ----------------------------

-- ----------------------------
-- Table structure for gz_notice
-- ----------------------------
DROP TABLE IF EXISTS `gz_notice`;
CREATE TABLE `gz_notice` (
  `nid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(128) NOT NULL COMMENT '标题',
  `text` text NOT NULL COMMENT '内容',
  `add_time` int(11) NOT NULL COMMENT '发布时间',
  `is_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `is_window` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否弹窗',
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_notice
-- ----------------------------

-- ----------------------------
-- Table structure for gz_order
-- ----------------------------
DROP TABLE IF EXISTS `gz_order`;
CREATE TABLE `gz_order` (
  `oid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `order_num` varchar(32) NOT NULL COMMENT '订单号',
  `uid` int(12) NOT NULL DEFAULT '0' COMMENT '用户ID(0为未登录)',
  `add_time` int(11) NOT NULL COMMENT '订单时间',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `telephone` text COMMENT '联系信息',
  `cid` int(11) DEFAULT NULL COMMENT '卡密ID',
  `card` text COMMENT '卡密信息',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态',
  `pay_type` tinyint(4) DEFAULT NULL COMMENT '支付方式',
  `acid` int(11) DEFAULT NULL COMMENT '支付账号ID',
  `pay_num` varchar(64) DEFAULT NULL COMMENT '支付交易号',
  `is_email` varchar(128) DEFAULT NULL COMMENT '是否发邮件',
  `is_sms` varchar(18) DEFAULT NULL COMMENT '是否发短信',
  `price` decimal(12,2) NOT NULL COMMENT '订单价格',
  `info_price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '含信息价格',
  `is_head` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否手动发货',
  `goods_time` int(11) DEFAULT NULL COMMENT '发货时间',
  `delete_time` int(11) NOT NULL DEFAULT '0',
  `goods_id` int(11) NOT NULL,
  `gsid` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '1',
  `account` varchar(16) DEFAULT NULL,
  `pwd` varchar(32) DEFAULT NULL,
  `cookie` varchar(32) NOT NULL,
  `user_delete_time` int(11) NOT NULL DEFAULT '0',
  `sale_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品单价',
  `pay_type_key` varchar(32) DEFAULT NULL COMMENT '支付方式key',
  `pay_result` text COMMENT '支付回调的原始数据',
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gz_program_config
-- ----------------------------
DROP TABLE IF EXISTS `gz_program_config`;
CREATE TABLE `gz_program_config` (
  `pcid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(16) NOT NULL COMMENT '功能标识',
  `config` text NOT NULL COMMENT '配置信息',
  `update_time` int(12) NOT NULL COMMENT '修改时间',
  `add_time` int(12) NOT NULL COMMENT '配置时间',
  PRIMARY KEY (`pcid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_program_config
-- ----------------------------

-- ----------------------------
-- Table structure for gz_program_un
-- ----------------------------
DROP TABLE IF EXISTS `gz_program_un`;
CREATE TABLE `gz_program_un` (
  `puid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `program` varchar(128) NOT NULL COMMENT '功能标识',
  `un` tinyint(1) NOT NULL DEFAULT '1' COMMENT '功能开关',
  `update_time` int(12) NOT NULL COMMENT '上次修改时间',
  PRIMARY KEY (`puid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_program_un
-- ----------------------------

-- ----------------------------
-- Table structure for gz_static
-- ----------------------------
DROP TABLE IF EXISTS `gz_static`;
CREATE TABLE `gz_static` (
  `sid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `key` varchar(12) NOT NULL COMMENT '统计标识',
  `date` int(12) NOT NULL COMMENT '统计日期戳',
  `add_time` int(12) NOT NULL COMMENT '统计时间',
  `num` decimal(12,3) NOT NULL COMMENT '统计结果',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_static
-- ----------------------------

-- ----------------------------
-- Table structure for gz_user
-- ----------------------------
DROP TABLE IF EXISTS `gz_user`;
CREATE TABLE `gz_user` (
  `uid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(32) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `salt` char(4) NOT NULL COMMENT '密码盐',
  `qq` varchar(11) DEFAULT NULL COMMENT 'QQ',
  `telephone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `nickname` varchar(32) NOT NULL COMMENT '昵称',
  `reg_time` int(11) NOT NULL COMMENT '注册时间',
  `login_time` int(11) NOT NULL COMMENT '上次登录时间',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态',
  `gid` int(12) NOT NULL DEFAULT '0' COMMENT '所属用户组',
  `delete_time` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `qqopenid` varchar(64) DEFAULT NULL,
  `qqinfo` text,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for gz_user_bind
-- ----------------------------
DROP TABLE IF EXISTS `gz_user_bind`;
CREATE TABLE `gz_user_bind` (
  `uid` int(12) NOT NULL COMMENT '用户ID',
  `qqkey` varchar(64) DEFAULT NULL COMMENT 'QQkey',
  `qqtime` int(12) DEFAULT NULL COMMENT 'QQ绑定时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_user_bind
-- ----------------------------

-- ----------------------------
-- Table structure for gz_user_group
-- ----------------------------
DROP TABLE IF EXISTS `gz_user_group`;
CREATE TABLE `gz_user_group` (
  `gid` int(12) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(32) NOT NULL COMMENT '用户组名称',
  `sort` int(8) NOT NULL DEFAULT '10' COMMENT '排序',
  `add_time` int(11) NOT NULL COMMENT '创建时间',
  `is_more` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of gz_user_group
-- ----------------------------
INSERT INTO `gz_user_group` VALUES ('1', '普通用户', '-1', '1600000000', '1');
INSERT INTO `gz_user_group` VALUES ('3', '初级代理', '10', '1636218715', '0');
INSERT INTO `gz_user_group` VALUES ('4', '高级代理', '10', '1636218861', '0');
