/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50721
Source Host           : localhost:3306
Source Database       : examsys

Target Server Type    : MYSQL
Target Server Version : 50721
File Encoding         : 65001

Date: 2019-02-07 18:23:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '菜单名称',
  `pid` varchar(100) DEFAULT NULL COMMENT '父菜单',
  `icon` varchar(30) DEFAULT NULL COMMENT '图标类',
  `url` varchar(100) DEFAULT NULL COMMENT 'url',
  PRIMARY KEY (`id`),
  KEY `IDX_PID` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='菜单表';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '题库管理', '0', null, '');
INSERT INTO `menu` VALUES ('2', '试卷管理', '0', null, null);
INSERT INTO `menu` VALUES ('3', '系统分析', '0', '', '');
INSERT INTO `menu` VALUES ('4', '用户管理', '0', '', '');
INSERT INTO `menu` VALUES ('5', '广告管理', '0', '', '');
INSERT INTO `menu` VALUES ('6', '新增题库', '1', '', 'db/create');
INSERT INTO `menu` VALUES ('7', '题库列表', '1', '', 'db');
INSERT INTO `menu` VALUES ('8', '新增试题', '1', '', 'question/create');
INSERT INTO `menu` VALUES ('9', '批量导入试题', '1', '', '');
INSERT INTO `menu` VALUES ('10', '试题管理', '1', '', 'question');
INSERT INTO `menu` VALUES ('11', '试卷管理', '2', '', '');
INSERT INTO `menu` VALUES ('12', '创建试卷', '2', '', '');
INSERT INTO `menu` VALUES ('13', '试卷分类管理', '2', '', '');
INSERT INTO `menu` VALUES ('14', '试卷分析', '3', '', '');
INSERT INTO `menu` VALUES ('15', '开始分析', '3', '', '');
INSERT INTO `menu` VALUES ('16', '成绩分析', '3', '', '');
INSERT INTO `menu` VALUES ('17', '公告列表', '5', '', '');
INSERT INTO `menu` VALUES ('18', '公告分类', '5', '', '');
INSERT INTO `menu` VALUES ('19', '用户管理', '4', '', '');
INSERT INTO `menu` VALUES ('20', '院系管理', '4', '', '');
INSERT INTO `menu` VALUES ('21', '班级管理', '4', '', '');

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '权限节点名称',
  `path` varchar(100) NOT NULL DEFAULT '' COMMENT '权限路径',
  `path_id` varchar(100) NOT NULL DEFAULT '' COMMENT '路径唯一编码',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态0未启用1正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_permission` (`path_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限节点';

-- ----------------------------
-- Records of permission
-- ----------------------------

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` varchar(50) NOT NULL,
  `dbid` varchar(50) DEFAULT NULL COMMENT '所属题库',
  `type` int(2) DEFAULT NULL COMMENT '题目类型 1选择题 2填空题 3判断题 4简答题 5多选题',
  `author` varchar(50) DEFAULT NULL COMMENT '出题人',
  `status` int(2) DEFAULT '1' COMMENT '状态',
  `title` text COMMENT '题目标题',
  `key` text COMMENT '参考答案',
  `resolve` text COMMENT '参考解析',
  `createdate` int(11) DEFAULT NULL,
  `data` text,
  `level` int(11) DEFAULT NULL COMMENT '难度',
  PRIMARY KEY (`id`),
  KEY `FK_ref_question_db` (`dbid`),
  KEY `IDX_QTYPE` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='题目表';

-- ----------------------------
-- Records of question
-- ----------------------------
INSERT INTO `question` VALUES ('5c581939256ad', '2', '1', null, '1', '如何创建线程', 'A', '题目解析', '1549277497', '{\"type\":\"1\",\"level\":\"3\",\"dbid\":\"2\",\"status\":\"1\",\"from\":\"\\u963f\\u65af\\u8482\\u82ac\",\"title\":\"\\u5982\\u4f55\\u521b\\u5efa\\u7ebf\\u7a0b\",\"key\":\"A\",\"item\":[\"\\u7b54\\u6848\\u554a\",\"\\u963f\\u65af\\u8482\\u82ac\",\"\\u963f\\u51e1\\u8fbe\",\"\\u53d1\\u7684\"],\"content\":\"\\u7b80\\u7b54\\u9898\\u7b54\\u6848\",\"resolve\":\"\\u9898\\u76ee\\u89e3\\u6790\",\"createdate\":1549277497}', '3');
INSERT INTO `question` VALUES ('5c5bac3f807a15c5bac3f807a3', '3', '5', null, '1', '简答题测试', '简答题答案', '题目解析', '1549511743', '{\"type\":\"5\",\"level\":\"3\",\"dbid\":\"2\",\"status\":\"1\",\"from\":\"test\",\"title\":\"\\u7b80\\u7b54\\u9898\\u6d4b\\u8bd5\",\"key\":\"\\u7b80\\u7b54\\u9898\\u7b54\\u6848\",\"resolve\":\"\\u9898\\u76ee\\u89e3\\u6790\",\"createdate\":1549511743}', '3');
INSERT INTO `question` VALUES ('5c5bac6638d1d5c5bac6638d1f', '4', '1', null, '1', '单选题测试', '简答题答案', '题目解析', '1549511782', '{\"type\":\"1\",\"level\":\"3\",\"dbid\":\"2\",\"status\":\"1\",\"from\":\"\",\"title\":\"\\u5355\\u9009\\u9898\\u6d4b\\u8bd5\",\"key\":\"\\u7b80\\u7b54\\u9898\\u7b54\\u6848\",\"item\":[\"\\u6b63\\u786e\\u7b54\\u6848\",\"\\u963f\\u65af\\u8482\\u82ac\",\"\\u963f\\u65af\\u8482\\u82ac\",\"\\u963f\\u65af\\u8482\\u82ac\"],\"resolve\":\"\\u9898\\u76ee\\u89e3\\u6790\",\"createdate\":1549511782}', '3');
INSERT INTO `question` VALUES ('5c5bac7e2674c5c5bac7e2674e', '2', '2', null, '1', '多选题测试', 'A,B', '题目解析', '1549511806', '{\"type\":\"2\",\"level\":\"3\",\"dbid\":\"2\",\"status\":\"1\",\"from\":\"\",\"title\":\"\\u591a\\u9009\\u9898\\u6d4b\\u8bd5\",\"key\":[\"A\",\"B\"],\"item\":[\"SADF\",\"\\u963f\\u65af\\u8482\\u82ac\",\"\\u963f\\u65af\\u8482\\u82ac\",\"\\u963f\\u65af\\u8482\\u82ac\"],\"resolve\":\"\\u9898\\u76ee\\u89e3\\u6790\",\"createdate\":1549511806}', '3');
INSERT INTO `question` VALUES ('5c5bac923f6cb5c5bac923f6ce', '3', '4', null, '1', '填空题测试', '[Blank]', '题目解析', '1549511826', '{\"type\":\"4\",\"level\":\"3\",\"dbid\":\"2\",\"status\":\"1\",\"from\":\"\",\"title\":\"\\u586b\\u7a7a\\u9898\\u6d4b\\u8bd5\",\"key\":\"[Blank]\",\"resolve\":\"\\u9898\\u76ee\\u89e3\\u6790\",\"createdate\":1549511826}', '3');
INSERT INTO `question` VALUES ('5c5bb319421b15c5bb319421b5', '2', '3', null, '1', '判断题测试', 'Y', '题目解析', '1549513497', '{\"type\":\"3\",\"level\":\"3\",\"dbid\":\"2\",\"status\":\"1\",\"from\":\"\",\"title\":\"\\u5224\\u65ad\\u9898\\u6d4b\\u8bd5\",\"key\":\"Y\",\"resolve\":\"\\u9898\\u76ee\\u89e3\\u6790\",\"createdate\":1549513497}', '3');

-- ----------------------------
-- Table structure for question_db
-- ----------------------------
DROP TABLE IF EXISTS `question_db`;
CREATE TABLE `question_db` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '题库名称',
  `status` int(2) DEFAULT NULL COMMENT '状态',
  `remark` varchar(200) DEFAULT NULL COMMENT '备注',
  `poster` varchar(50) DEFAULT NULL COMMENT '创建者',
  `createdate` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='题库表';

-- ----------------------------
-- Records of question_db
-- ----------------------------
INSERT INTO `question_db` VALUES ('2', 'JavaEE', '1', 'JavaEE', null, '1549274581');
INSERT INTO `question_db` VALUES ('3', 'PHP题库', '1', 'php相关题目', null, '1549274537');
INSERT INTO `question_db` VALUES ('4', '大学英语(一)题库', '1', '大学英语(一)题库', null, '1549274581');

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id（一般不建议角色的继承）',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '角色描述',
  `status` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '状态1正常0未启用',
  `sort_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序值',
  `left_key` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '左值',
  `right_key` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '右值',
  `level` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '层级',
  PRIMARY KEY (`id`),
  KEY `idx_role` (`status`,`left_key`,`right_key`,`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色';

-- ----------------------------
-- Records of role
-- ----------------------------

-- ----------------------------
-- Table structure for role_permission
-- ----------------------------
DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE `role_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色编号',
  `permission_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限对应表';

-- ----------------------------
-- Records of role_permission
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) DEFAULT NULL COMMENT '用户名',
  `student_id` varchar(50) DEFAULT NULL COMMENT '学号',
  `password` varchar(50) DEFAULT NULL COMMENT '密码',
  `mobile` char(11) DEFAULT NULL COMMENT '手机号码',
  `user_type` char(1) DEFAULT NULL COMMENT '1表示管理官 2表示教师  3表示学生',
  `last_login_time` int(11) DEFAULT '0' COMMENT '最后登录时间',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '修改时间',
  `realname` varchar(50) DEFAULT NULL COMMENT '真实名字',
  `photo` varchar(50) DEFAULT NULL COMMENT '照片',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `status` int(2) DEFAULT '1' COMMENT '状态，1表示正常',
  `salt` varchar(10) DEFAULT NULL COMMENT '盐值',
  `remark` varchar(500) DEFAULT NULL COMMENT '备注信息',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `user_type` (`user_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'zrlee', null, 'ex67EYYFcbzAc', null, null, '0', '0', '0', null, null, null, '1', null, null);

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户角色对应关系';

-- ----------------------------
-- Records of user_role
-- ----------------------------
