/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : cjbxxq

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-01-24 11:23:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sd_admin
-- ----------------------------
DROP TABLE IF EXISTS `sd_admin`;
CREATE TABLE `sd_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `login_times` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `last_login_ip` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `real_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '真实姓名',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `role_id` int(11) NOT NULL DEFAULT '1' COMMENT '用户角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of sd_admin
-- ----------------------------
INSERT INTO `sd_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '95', '127.0.0.1', '1516691148', 'admin', '1', '1');
INSERT INTO `sd_admin` VALUES ('3', 'user', 'e10adc3949ba59abbe56e057f20f883e', '1', '127.0.0.1', '1515480964', 'user', '1', '2');

-- ----------------------------
-- Table structure for sd_articles
-- ----------------------------
DROP TABLE IF EXISTS `sd_articles`;
CREATE TABLE `sd_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(155) NOT NULL COMMENT '文章标题',
  `description` varchar(255) NOT NULL COMMENT '文章描述',
  `keywords` varchar(155) NOT NULL COMMENT '文章关键字',
  `thumbnail` varchar(255) NOT NULL COMMENT '文章缩略图',
  `content` text NOT NULL COMMENT '文章内容',
  `add_time` datetime NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_articles
-- ----------------------------
INSERT INTO `sd_articles` VALUES ('2', '文章标题', '文章描述', '关键字1,关键字2,关键字3', '/upload/20170916/1e915c70dbb9d3e8a07bede7b64e4cff.png', '<p><img src=\"/upload/image/20170916/1505555254.png\" title=\"1505555254.png\" alt=\"QQ截图20170916174651.png\"/></p><p>测试文章内容</p><p>测试内容</p>', '2017-09-16 17:47:44');

-- ----------------------------
-- Table structure for sd_award
-- ----------------------------
DROP TABLE IF EXISTS `sd_award`;
CREATE TABLE `sd_award` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '奖品id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `thumd` varchar(255) NOT NULL DEFAULT '' COMMENT '奖品缩略图',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '奖品类型',
  `chance` int(10) NOT NULL DEFAULT '0' COMMENT '中奖概率',
  `num` int(10) NOT NULL DEFAULT '0' COMMENT '奖品数量',
  `desc` text NOT NULL COMMENT '奖品描述',
  `api_id` int(10) NOT NULL DEFAULT '0' COMMENT 'api对应id',
  `api_link` varchar(255) NOT NULL DEFAULT '' COMMENT '领取奖品url',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '添加人',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `store_address` varchar(150) NOT NULL DEFAULT '' COMMENT '门店地址',
  `store_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '门店联系电话',
  `discount` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '门店折扣',
  `login_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '登录电话',
  `login_password` varchar(50) NOT NULL DEFAULT '' COMMENT '登录密码',
  `is_modify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否修改密码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_award
-- ----------------------------
INSERT INTO `sd_award` VALUES ('1', '奖品1', '/upload/award/20171214/aca817bf638f9d797adaeadfdf737225.png', '1', '1000', '100', '奖品1奖品1', '123', '', '1513228392', '1513228392', '1', '0', '', '', '0.00', '', '', '0');
INSERT INTO `sd_award` VALUES ('2', '奖品2', '/upload/award/20171214/34b80507ab3424672052d969c21fafeb.png', '1', '1000', '100', '奖品2奖品2', '122', '', '1513229524', '1513229524', '1', '0', '', '', '0.00', '', '', '0');
INSERT INTO `sd_award` VALUES ('3', '奖品3', '/upload/award/20171214/cfdd03764a26e2113400a9d07fd5cb3e.png', '1', '1000', '100', '奖品3奖品3', '121', '', '1513229614', '1513236814', '1', '0', '', '', '0.00', '', '', '0');
INSERT INTO `sd_award` VALUES ('4', '百度外卖', '/upload/award/20171214/bde9426fabee64a992f2dde18922038d.png', '1', '0', '100', '百度外卖百度外卖2', '123', '', '1513237054', '1516086805', '1', '0', '', '', '9.50', '15828230590', '99126d62b5ffb73fc89f9ae5f0ce60ae', '0');
INSERT INTO `sd_award` VALUES ('5', '大龙火锅(春熙路概念店)', '/upload/award/20180116/9321255c83d687098d2aec6226c27b4b.png', '1', '1000', '100', '大龙火锅(春熙路概念店)大龙火锅(春熙路概念店)', '0', '', '1516086943', '1516086943', '1', '0', '成都市锦江区红星路四段25号附23号 2楼', '028-65773893', '7.50', '15828230599', 'e993e6e2937199578bbe79883ba2c30e', '0');

-- ----------------------------
-- Table structure for sd_award_log
-- ----------------------------
DROP TABLE IF EXISTS `sd_award_log`;
CREATE TABLE `sd_award_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  `member_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '会员电话',
  `award_id` int(10) NOT NULL DEFAULT '0' COMMENT '奖品id',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '中奖时间',
  `link_url` varchar(255) NOT NULL DEFAULT '' COMMENT '虚拟奖品链接',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '实物奖品中奖者电话',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '实物奖品中奖者地址',
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用或兑换',
  `code_str` varchar(10) NOT NULL DEFAULT '' COMMENT '随机码',
  `is_validate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否验证',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_award_log
-- ----------------------------

-- ----------------------------
-- Table structure for sd_award_record
-- ----------------------------
DROP TABLE IF EXISTS `sd_award_record`;
CREATE TABLE `sd_award_record` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_award_record
-- ----------------------------

-- ----------------------------
-- Table structure for sd_coupons
-- ----------------------------
DROP TABLE IF EXISTS `sd_coupons`;
CREATE TABLE `sd_coupons` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '线下优惠券id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `desc` text NOT NULL COMMENT '优惠券描述',
  `thumd` varchar(100) NOT NULL DEFAULT '' COMMENT '活动缩略图',
  `discount` float(3,2) NOT NULL DEFAULT '0.00' COMMENT '折扣',
  `store_address` varchar(255) NOT NULL DEFAULT '' COMMENT '门店地址',
  `store_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '联系电话',
  `start_time` int(10) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `end_time` int(10) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `class_id` int(10) NOT NULL DEFAULT '0' COMMENT '分类id',
  `is_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `login_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '登录电话',
  `login_password` varchar(50) NOT NULL DEFAULT '' COMMENT '登录密码',
  `is_modify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '密码是否修改',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_coupons
-- ----------------------------
INSERT INTO `sd_coupons` VALUES ('1', '小龙坎7折', '1、客户凭优惠劵到店消费享受5折优惠，最高满400元立减200元每人每劵限消费一桌，单日优惠封顶200元，超出部分按原价支付。<br/><br/>\n\n 2、优惠劵使用仅限客户到店堂食，不予外卖或打包使用；店内特价菜、套餐、酒水软饮、火锅类锅底不做优惠范以内。<br/><br/>\n\n3、客户需使用邮储银行借记卡、信用卡、银联二维码支付消费的可享消费优惠。', '/upload/coupons/20180105/ec4bd274859b593d6c6158a9c9010904.png', '8.50', '环球中心N5', '028-65773893', '1515081600', '1517328000', '1515122271', '1', '1', '1', '0', '15828230592', 'e4d2e89649c43574102b2c14ddc4c5e9', '0');
INSERT INTO `sd_coupons` VALUES ('2', '大龙火锅(春熙路概念店)', '大龙火锅(春熙路概念店)大龙火锅(春熙路概念店)', '/upload/coupons/20180115/069d6c38caf1acb40c6686621075a744.png', '5.00', '成都市锦江区红星路四段25号附23号 2楼', '028-65773893', '1515945600', '1517328000', '1516004448', '1', '1', '1', '1', '15828230599', 'e993e6e2937199578bbe79883ba2c30e', '0');
INSERT INTO `sd_coupons` VALUES ('3', '贺氏洪七公吃串串（总府店）', '贺氏洪七公吃串串（总府店）贺氏洪七公吃串串（总府店）', '', '7.50', '成都市锦江区三倒拐街25号', '028-86934866', '1515945600', '1519747200', '1516004721', '1', '1', '1', '1', '15828230590', '99126d62b5ffb73fc89f9ae5f0ce60ae', '0');
INSERT INTO `sd_coupons` VALUES ('4', '大龙火锅(春熙路概念店)', '大龙火锅(春熙路概念店)大龙火锅(春熙路概念店)大龙火锅(春熙路概念店)', '/upload/coupons/20180115/09b02406c6c5a212b0291356663206c0.png', '9.85', '成都市锦江区红星路四段25号附23号 2楼', '028-65773893', '1515945600', '1517328000', '1516004858', '1', '1', '1', '1', '15828230593', 'e829d8f1fe0e647a1183970a71ae093a', '0');

-- ----------------------------
-- Table structure for sd_coupons_class
-- ----------------------------
DROP TABLE IF EXISTS `sd_coupons_class`;
CREATE TABLE `sd_coupons_class` (
  `class_id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `class_name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `class_thumd` varchar(150) NOT NULL DEFAULT '' COMMENT '分类缩略图',
  `class_sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_coupons_class
-- ----------------------------
INSERT INTO `sd_coupons_class` VALUES ('1', '美食类商户', '1515555527', '/upload/coupons_class/20180115/8f48f4d7ddc093475cfe5794b51942e2.png', '0');
INSERT INTO `sd_coupons_class` VALUES ('2', '居家类商户', '1515563133', '/upload/coupons_class/20180115/acebd57a59d1b5719bffc4e2b43430db.png', '0');
INSERT INTO `sd_coupons_class` VALUES ('4', '亲子类商户', '1516005516', '/upload/coupons_class/20180115/24352feda6864e7e2099bb762435e2de.png', '0');

-- ----------------------------
-- Table structure for sd_coupons_log
-- ----------------------------
DROP TABLE IF EXISTS `sd_coupons_log`;
CREATE TABLE `sd_coupons_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  `member_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '会员电话',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '中奖时间',
  `coupons_id` int(10) NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用',
  `code_str` varchar(10) NOT NULL DEFAULT '' COMMENT '随机码',
  `is_validate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否验证',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_coupons_log
-- ----------------------------
INSERT INTO `sd_coupons_log` VALUES ('1', '1', '15858282359', '0', '1', '0', '', '0');
INSERT INTO `sd_coupons_log` VALUES ('2', '1', '15858282359', '1516601809', '4', '0', '', '0');
INSERT INTO `sd_coupons_log` VALUES ('3', '1', '15858282359', '1516602132', '3', '0', '', '0');

-- ----------------------------
-- Table structure for sd_member
-- ----------------------------
DROP TABLE IF EXISTS `sd_member`;
CREATE TABLE `sd_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `openid` varchar(100) NOT NULL DEFAULT '' COMMENT '微信openid',
  `member_name` varchar(50) NOT NULL DEFAULT '' COMMENT '会员名称',
  `member_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '会员电话',
  `member_email` varchar(50) NOT NULL DEFAULT '' COMMENT '会员邮箱',
  `real_name` varchar(10) NOT NULL DEFAULT '' COMMENT '真实名称',
  `nike_name` varchar(50) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `lottery` int(10) NOT NULL DEFAULT '50' COMMENT '抽奖次数',
  `is_use` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否运行使用',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '会员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_member
-- ----------------------------
INSERT INTO `sd_member` VALUES ('1', '', '15858282359', '15858282359', '', '', '', '0', '0', '50', '1', '1');

-- ----------------------------
-- Table structure for sd_node
-- ----------------------------
DROP TABLE IF EXISTS `sd_node`;
CREATE TABLE `sd_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(155) NOT NULL DEFAULT '' COMMENT '节点名称',
  `control_name` varchar(155) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(155) NOT NULL COMMENT '方法名',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',
  `type_id` int(11) NOT NULL COMMENT '父级节点id',
  `style` varchar(155) DEFAULT '' COMMENT '菜单样式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of sd_node
-- ----------------------------
INSERT INTO `sd_node` VALUES ('1', '管理员管理', '#', '#', '2', '0', 'fa fa-users');
INSERT INTO `sd_node` VALUES ('2', '管理员管理', 'admin', 'index', '2', '1', '');
INSERT INTO `sd_node` VALUES ('3', '添加管理员', 'admin', 'adminadd', '1', '2', '');
INSERT INTO `sd_node` VALUES ('4', '编辑管理员', 'admin', 'adminedit', '1', '2', '');
INSERT INTO `sd_node` VALUES ('5', '删除管理员', 'admin', 'admindel', '1', '2', '');
INSERT INTO `sd_node` VALUES ('6', '角色管理', 'role', 'index', '2', '1', '');
INSERT INTO `sd_node` VALUES ('7', '添加角色', 'role', 'roleadd', '1', '6', '');
INSERT INTO `sd_node` VALUES ('8', '编辑角色', 'role', 'roleedit', '1', '6', '');
INSERT INTO `sd_node` VALUES ('9', '删除角色', 'role', 'roledel', '1', '6', '');
INSERT INTO `sd_node` VALUES ('10', '分配权限', 'role', 'giveaccess', '1', '6', '');
INSERT INTO `sd_node` VALUES ('11', '系统管理', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `sd_node` VALUES ('12', '数据备份/还原', 'system', 'indexdata', '2', '11', '');
INSERT INTO `sd_node` VALUES ('13', '备份数据', 'system', 'importdata', '1', '12', '');
INSERT INTO `sd_node` VALUES ('14', '还原数据', 'system', 'backdata', '1', '12', '');
INSERT INTO `sd_node` VALUES ('15', '节点管理', 'node', 'index', '2', '1', '');
INSERT INTO `sd_node` VALUES ('16', '添加节点', 'node', 'nodeadd', '1', '15', '');
INSERT INTO `sd_node` VALUES ('17', '编辑节点', 'node', 'nodeedit', '1', '15', '');
INSERT INTO `sd_node` VALUES ('18', '删除节点', 'node', 'nodedel', '1', '15', '');
INSERT INTO `sd_node` VALUES ('24', '会员管理', '#', '#', '2', '0', 'fa fa-user');
INSERT INTO `sd_node` VALUES ('25', '会员管理', 'member', 'index', '2', '24', '');
INSERT INTO `sd_node` VALUES ('26', '删除会员', 'member', 'member_del', '1', '25', '');
INSERT INTO `sd_node` VALUES ('27', '抽奖管理', '#', '#', '2', '0', 'fa fa-bars');
INSERT INTO `sd_node` VALUES ('28', '奖品管理', 'award', 'award_list', '2', '27', '');
INSERT INTO `sd_node` VALUES ('29', '添加奖品', 'award', 'award_add', '1', '28', '');
INSERT INTO `sd_node` VALUES ('32', '删除奖品', 'award', 'award_del', '1', '28', '');
INSERT INTO `sd_node` VALUES ('33', '编辑奖品', 'award', 'award_edit', '1', '28', '');
INSERT INTO `sd_node` VALUES ('34', '中奖记录', 'award', 'award_log', '2', '27', '');
INSERT INTO `sd_node` VALUES ('35', '门店优惠券管理', '#', '#', '2', '0', 'fa fa-newspaper-o');
INSERT INTO `sd_node` VALUES ('36', '优惠券管理', 'coupons', 'coupons_list', '2', '35', '');
INSERT INTO `sd_node` VALUES ('37', '添加优惠券', 'coupons', 'coupons_add', '1', '36', '');
INSERT INTO `sd_node` VALUES ('38', '删除优惠券', 'coupons', 'coupons_del', '1', '36', '');
INSERT INTO `sd_node` VALUES ('39', '编辑优惠券', 'coupons', 'coupons_edit', '1', '36', '');
INSERT INTO `sd_node` VALUES ('40', '用户记录', '#', '#', '2', '0', 'fa fa-cube');
INSERT INTO `sd_node` VALUES ('41', 'ETC记录', 'record', 'etc_list', '2', '40', '');
INSERT INTO `sd_node` VALUES ('42', '社保记录', 'record', 'social_list', '2', '40', '');
INSERT INTO `sd_node` VALUES ('43', '删除etc记录', 'record', 'etc_del', '1', '41', '');
INSERT INTO `sd_node` VALUES ('44', '删除社保记录', 'record', 'social', '1', '42', '');
INSERT INTO `sd_node` VALUES ('45', '导出excel', 'record', 'etc_export', '1', '41', '');
INSERT INTO `sd_node` VALUES ('46', '导出excel', 'record', 'social_export', '1', '42', '');
INSERT INTO `sd_node` VALUES ('47', '门店管理', 'coupons', 'store_list', '1', '35', '');
INSERT INTO `sd_node` VALUES ('48', '添加门店', 'coupons', 'store_add', '1', '47', '');
INSERT INTO `sd_node` VALUES ('49', '门店编辑', 'coupons', 'store_edit', '1', '47', '');
INSERT INTO `sd_node` VALUES ('50', '删除门店', 'coupons', 'store_del', '1', '47', '');
INSERT INTO `sd_node` VALUES ('52', '导出中奖记录', 'award', 'award_explod', '1', '34', '');
INSERT INTO `sd_node` VALUES ('53', '更改兑换状态', 'award', 'award_log_edit', '1', '34', '');
INSERT INTO `sd_node` VALUES ('54', ' 优惠券分类', 'coupons', 'coupons_class_list', '2', '35', '');
INSERT INTO `sd_node` VALUES ('55', '添加分类', 'coupons', 'coupons_class_add', '1', '54', '');
INSERT INTO `sd_node` VALUES ('56', '编辑分类', 'coupons', 'coupons_class_edit', '1', '54', '');
INSERT INTO `sd_node` VALUES ('57', '删除分类', 'coupons', 'coupons_class_del', '1', '54', '');
INSERT INTO `sd_node` VALUES ('58', '领取优惠券记录', 'coupons', 'coupons_log', '2', '35', '');
INSERT INTO `sd_node` VALUES ('59', '重置密码', 'coupons', 'reset_pass', '1', '36', '');
INSERT INTO `sd_node` VALUES ('60', '重置密码', 'award', 'reset_pass', '1', '28', '');

-- ----------------------------
-- Table structure for sd_record_etc
-- ----------------------------
DROP TABLE IF EXISTS `sd_record_etc`;
CREATE TABLE `sd_record_etc` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `etc_name` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `etc_phone` varchar(11) NOT NULL DEFAULT '' COMMENT '电话',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_record_etc
-- ----------------------------

-- ----------------------------
-- Table structure for sd_record_social
-- ----------------------------
DROP TABLE IF EXISTS `sd_record_social`;
CREATE TABLE `sd_record_social` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `s_name` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `s_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '会员id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_record_social
-- ----------------------------

-- ----------------------------
-- Table structure for sd_role
-- ----------------------------
DROP TABLE IF EXISTS `sd_role`;
CREATE TABLE `sd_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(155) NOT NULL COMMENT '角色名称',
  `rule` varchar(255) DEFAULT '' COMMENT '权限节点数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of sd_role
-- ----------------------------
INSERT INTO `sd_role` VALUES ('1', '超级管理员', '*');
INSERT INTO `sd_role` VALUES ('2', '系统维护员', '24,25,26,35,36,37,38,39,47,48,49,50,54,55,56,57,58');

-- ----------------------------
-- Table structure for sd_store
-- ----------------------------
DROP TABLE IF EXISTS `sd_store`;
CREATE TABLE `sd_store` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `store_name` varchar(100) NOT NULL DEFAULT '' COMMENT '门店名称',
  `store_thumd` varchar(100) NOT NULL DEFAULT '' COMMENT '门店缩略图',
  `store_address` varchar(100) NOT NULL DEFAULT '' COMMENT '门店地址',
  `store_phone` varchar(15) NOT NULL DEFAULT '' COMMENT '门店电话',
  `store_unique` varchar(50) NOT NULL DEFAULT '' COMMENT '门店唯一标识',
  `coupons_id` int(10) NOT NULL DEFAULT '0' COMMENT '门店优惠券id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `add_time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '门店状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sd_store
-- ----------------------------
