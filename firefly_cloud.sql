/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost:3306
 Source Schema         : firefly_cloud

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : 65001

 Date: 23/04/2019 09:27:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `nickname` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户昵称',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '密码',
  `salt` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '密码盐',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像链接',
  `gender` tinyint(1) NOT NULL DEFAULT 0 COMMENT '性别[0:保密,1:女,2:.男]',
  `born` date NULL DEFAULT NULL COMMENT '出生年月',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `tel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '电话',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '令牌',
  `login_fail` tinyint(2) NOT NULL DEFAULT 0 COMMENT '登录失败',
  `vcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '验证码',
  `role` tinyint(2) NOT NULL DEFAULT 0 COMMENT '权限[0:系统管理员,1:普通管理员]',
  `weigh` bigint(20) NOT NULL DEFAULT 1 COMMENT '权重',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '注册时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (3, 'admin', '纵横', '23ef7dd2165b0ebf8d55b808c6bbe04168deca8f', 'n#Hd.', '/static/source/img/admin.jpeg', 1, '2019-04-03', '', '', 'd3fb905de94a08ccfda77a0d16269973', 0, NULL, 0, 1, 1, 0, 1554301977, 1555228656);
INSERT INTO `admin` VALUES (4, 'testa', '测试管理员', '30ff4092fe67c38ef3e16ce7d705f81194d1df69', 'id*w2', '/uploads/admin//20190416/d61d592bedccff58fa181137a35e6d1f.jpeg', 0, '2019-04-14', '', '', '', 0, '', 1, 4, 1, 0, 1555249855, 1555384736);

-- ----------------------------
-- Table structure for admin_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_log`;
CREATE TABLE `admin_log`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `admin_id` bigint(20) NULL DEFAULT NULL COMMENT '管理员编号',
  `ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '来源链接',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '当前链接',
  `table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '表名',
  `info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '详情',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'ip地址',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '地理位置',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户来源信息',
  `admin_log_type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '日志类型[0:增,1:删,2:改,3:查,4:登录,5:退出,6:邮件,7:图片]',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 239 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_log
-- ----------------------------
INSERT INTO `admin_log` VALUES (2, 1, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554030380, 1554030380);
INSERT INTO `admin_log` VALUES (3, 1, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554031192, 1554031192);
INSERT INTO `admin_log` VALUES (4, 1, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554031586, 1554031586);
INSERT INTO `admin_log` VALUES (5, 1, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554122444, 1554122444);
INSERT INTO `admin_log` VALUES (6, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216214, 1554216214);
INSERT INTO `admin_log` VALUES (7, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216274, 1554216274);
INSERT INTO `admin_log` VALUES (8, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216346, 1554216346);
INSERT INTO `admin_log` VALUES (9, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216357, 1554216357);
INSERT INTO `admin_log` VALUES (10, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216455, 1554216455);
INSERT INTO `admin_log` VALUES (11, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216486, 1554216486);
INSERT INTO `admin_log` VALUES (12, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216515, 1554216515);
INSERT INTO `admin_log` VALUES (13, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216569, 1554216569);
INSERT INTO `admin_log` VALUES (14, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216600, 1554216600);
INSERT INTO `admin_log` VALUES (15, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216621, 1554216621);
INSERT INTO `admin_log` VALUES (16, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216757, 1554216757);
INSERT INTO `admin_log` VALUES (17, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216781, 1554216781);
INSERT INTO `admin_log` VALUES (18, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216914, 1554216914);
INSERT INTO `admin_log` VALUES (19, 2, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554216950, 1554216950);
INSERT INTO `admin_log` VALUES (20, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554301993, 1554301993);
INSERT INTO `admin_log` VALUES (21, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554377724, 1554377724);
INSERT INTO `admin_log` VALUES (22, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554439347, 1554439347);
INSERT INTO `admin_log` VALUES (23, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554439384, 1554439384);
INSERT INTO `admin_log` VALUES (24, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554439415, 1554439415);
INSERT INTO `admin_log` VALUES (25, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554439430, 1554439430);
INSERT INTO `admin_log` VALUES (26, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554439440, 1554439440);
INSERT INTO `admin_log` VALUES (27, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554439542, 1554439542);
INSERT INTO `admin_log` VALUES (28, 3, 'http://www.firefly.test/admin/index/index2.html', 'http://www.firefly.test/admin/form/edit_password.html', 'admin', '修改密码成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554451880, 1554451880);
INSERT INTO `admin_log` VALUES (29, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554451893, 1554451893);
INSERT INTO `admin_log` VALUES (30, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554479553, 1554479553);
INSERT INTO `admin_log` VALUES (31, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554505215, 1554505215);
INSERT INTO `admin_log` VALUES (32, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554598212, 1554598212);
INSERT INTO `admin_log` VALUES (33, NULL, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1554648021, 1554648021);
INSERT INTO `admin_log` VALUES (34, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554648035, 1554648035);
INSERT INTO `admin_log` VALUES (35, NULL, 'http://www.firefly.test/admin/index/index.html', 'http://www.firefly.test/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1554648042, 1554648042);
INSERT INTO `admin_log` VALUES (36, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554648055, 1554648055);
INSERT INTO `admin_log` VALUES (37, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '状态修改成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554727980, 1554727980);
INSERT INTO `admin_log` VALUES (38, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '状态修改成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554727988, 1554727988);
INSERT INTO `admin_log` VALUES (39, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/data_table_event.html', 'menu', '删除id:10成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1554735718, 1554735718);
INSERT INTO `admin_log` VALUES (40, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/data_table_event.html', 'menu', '删除id:9成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1554735736, 1554735736);
INSERT INTO `admin_log` VALUES (41, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/data_table_event.html', 'menu', '删除id:16,15,14,13,12,11,10,9,8,7成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1554735770, 1554735770);
INSERT INTO `admin_log` VALUES (42, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554808994, 1554808994);
INSERT INTO `admin_log` VALUES (43, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '状态修改成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554809122, 1554809122);
INSERT INTO `admin_log` VALUES (44, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '状态修改成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554809126, 1554809126);
INSERT INTO `admin_log` VALUES (45, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:17]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1554906451, 1554906451);
INSERT INTO `admin_log` VALUES (46, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:18]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1554906750, 1554906750);
INSERT INTO `admin_log` VALUES (47, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:19]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1554907103, 1554907103);
INSERT INTO `admin_log` VALUES (48, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '修改[id:19]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554907226, 1554907226);
INSERT INTO `admin_log` VALUES (49, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '修改[id:18]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554907228, 1554907228);
INSERT INTO `admin_log` VALUES (50, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '修改[id:17]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554907229, 1554907229);
INSERT INTO `admin_log` VALUES (51, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:22]数据项成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1554907755, 1554907755);
INSERT INTO `admin_log` VALUES (52, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554983880, 1554983880);
INSERT INTO `admin_log` VALUES (53, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '修改[id:22]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554988720, 1554988720);
INSERT INTO `admin_log` VALUES (54, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '修改[id:21]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554988721, 1554988721);
INSERT INTO `admin_log` VALUES (55, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '修改[id:20]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554988722, 1554988722);
INSERT INTO `admin_log` VALUES (56, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'menu', '修改[id:19]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1554988723, 1554988723);
INSERT INTO `admin_log` VALUES (57, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555073463, 1555073463);
INSERT INTO `admin_log` VALUES (58, 3, 'http://www.firefly.test/admin/index/index.html', 'http://www.firefly.test/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1555073532, 1555073532);
INSERT INTO `admin_log` VALUES (59, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555073537, 1555073537);
INSERT INTO `admin_log` VALUES (60, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/data_table_event.html', 'menu', '删除[id:22,21,20,19,18,17]成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1555073588, 1555073588);
INSERT INTO `admin_log` VALUES (61, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:16]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555073609, 1555073609);
INSERT INTO `admin_log` VALUES (62, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:13]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555073635, 1555073635);
INSERT INTO `admin_log` VALUES (63, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:1]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555076166, 1555076166);
INSERT INTO `admin_log` VALUES (64, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:2]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555076275, 1555076275);
INSERT INTO `admin_log` VALUES (65, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/data_table_event.html', 'system_config', '删除[id:2]成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1555076439, 1555076439);
INSERT INTO `admin_log` VALUES (66, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555076447, 1555076447);
INSERT INTO `admin_log` VALUES (67, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/data_table_event.html', 'system_config', '删除[id:3]成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 1, 1, 0, 1555076469, 1555076469);
INSERT INTO `admin_log` VALUES (68, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555083995, 1555083995);
INSERT INTO `admin_log` VALUES (69, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555116593, 1555116593);
INSERT INTO `admin_log` VALUES (70, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555156401, 1555156401);
INSERT INTO `admin_log` VALUES (71, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'system_config', '修改[id:4]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555157700, 1555157700);
INSERT INTO `admin_log` VALUES (72, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/dtb_status.html', 'system_config', '修改[id:4]的状态成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555157702, 1555157702);
INSERT INTO `admin_log` VALUES (73, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/upload.html', 'system_config', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555158035, 1555158035);
INSERT INTO `admin_log` VALUES (74, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555158119, 1555158119);
INSERT INTO `admin_log` VALUES (75, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555163946, 1555163946);
INSERT INTO `admin_log` VALUES (76, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:5]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555164109, 1555164109);
INSERT INTO `admin_log` VALUES (77, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:6]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555164194, 1555164194);
INSERT INTO `admin_log` VALUES (78, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:6]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555164217, 1555164217);
INSERT INTO `admin_log` VALUES (79, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:7]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555164257, 1555164257);
INSERT INTO `admin_log` VALUES (80, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555164280, 1555164280);
INSERT INTO `admin_log` VALUES (81, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:8]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555164318, 1555164318);
INSERT INTO `admin_log` VALUES (82, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:9]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165007, 1555165007);
INSERT INTO `admin_log` VALUES (83, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:10]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165046, 1555165046);
INSERT INTO `admin_log` VALUES (84, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:11]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165069, 1555165069);
INSERT INTO `admin_log` VALUES (85, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:12]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165183, 1555165183);
INSERT INTO `admin_log` VALUES (86, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:13]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165254, 1555165254);
INSERT INTO `admin_log` VALUES (87, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:13]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555165280, 1555165280);
INSERT INTO `admin_log` VALUES (88, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:12]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555165289, 1555165289);
INSERT INTO `admin_log` VALUES (89, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:14]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165444, 1555165444);
INSERT INTO `admin_log` VALUES (90, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:15]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165559, 1555165559);
INSERT INTO `admin_log` VALUES (91, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:16]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165635, 1555165635);
INSERT INTO `admin_log` VALUES (92, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:17]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165682, 1555165682);
INSERT INTO `admin_log` VALUES (93, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:18]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165733, 1555165733);
INSERT INTO `admin_log` VALUES (94, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:19]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165786, 1555165786);
INSERT INTO `admin_log` VALUES (95, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:20]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165819, 1555165819);
INSERT INTO `admin_log` VALUES (96, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_add.html', 'system_config', '添加[id:21]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555165861, 1555165861);
INSERT INTO `admin_log` VALUES (97, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:7]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555166238, 1555166238);
INSERT INTO `admin_log` VALUES (98, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:7]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555166303, 1555166303);
INSERT INTO `admin_log` VALUES (99, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:9]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555166377, 1555166377);
INSERT INTO `admin_log` VALUES (100, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555203124, 1555203124);
INSERT INTO `admin_log` VALUES (101, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1555213411, 1555213411);
INSERT INTO `admin_log` VALUES (102, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555213447, 1555213447);
INSERT INTO `admin_log` VALUES (103, 3, 'http://www.firefly.test/admin/index/index.html', 'http://www.firefly.test/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1555213479, 1555213479);
INSERT INTO `admin_log` VALUES (104, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555213486, 1555213486);
INSERT INTO `admin_log` VALUES (105, 3, 'http://www.firefly.test/admin/system/system_config.html', 'http://www.firefly.test/admin/form/event_edit.html', 'system_config', '修改[id:9]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555215998, 1555215998);
INSERT INTO `admin_log` VALUES (106, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555221097, 1555221097);
INSERT INTO `admin_log` VALUES (107, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555221105, 1555221105);
INSERT INTO `admin_log` VALUES (108, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555221108, 1555221108);
INSERT INTO `admin_log` VALUES (109, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555221112, 1555221112);
INSERT INTO `admin_log` VALUES (110, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555221116, 1555221116);
INSERT INTO `admin_log` VALUES (111, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555221267, 1555221267);
INSERT INTO `admin_log` VALUES (112, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555221273, 1555221273);
INSERT INTO `admin_log` VALUES (113, 3, 'http://www.firefly.test/admin/system/userinfo.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555228656, 1555228656);
INSERT INTO `admin_log` VALUES (114, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:23]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555239389, 1555239389);
INSERT INTO `admin_log` VALUES (115, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:23]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555239420, 1555239420);
INSERT INTO `admin_log` VALUES (116, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:23]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555239462, 1555239462);
INSERT INTO `admin_log` VALUES (117, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:24]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555239489, 1555239489);
INSERT INTO `admin_log` VALUES (118, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:23]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555239518, 1555239518);
INSERT INTO `admin_log` VALUES (119, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555239541, 1555239541);
INSERT INTO `admin_log` VALUES (120, 3, 'http://www.firefly.test/admin/user/admin.html', 'http://www.firefly.test/admin/form/upload.html', 'admin', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555249943, 1555249943);
INSERT INTO `admin_log` VALUES (121, 3, 'http://www.firefly.test/admin/user/admin.html', 'http://www.firefly.test/admin/form/event_add.html', 'admin', '添加[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555250006, 1555250006);
INSERT INTO `admin_log` VALUES (122, 3, 'http://www.firefly.test/admin/user/admin.html', 'http://www.firefly.test/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1555250050, 1555250050);
INSERT INTO `admin_log` VALUES (123, 4, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555250064, 1555250064);
INSERT INTO `admin_log` VALUES (124, 4, 'http://www.firefly.test/admin/index/index.html', 'http://www.firefly.test/admin/form/edit_password.html', 'admin', '修改密码成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555250088, 1555250088);
INSERT INTO `admin_log` VALUES (125, 4, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555250101, 1555250101);
INSERT INTO `admin_log` VALUES (126, 4, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:24]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555250515, 1555250515);
INSERT INTO `admin_log` VALUES (127, 4, 'http://www.firefly.test/admin/user/admin.html', 'http://www.firefly.test/admin/form/event_edit.html', 'admin', '修改[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555250584, 1555250584);
INSERT INTO `admin_log` VALUES (128, 4, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:25]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555250929, 1555250929);
INSERT INTO `admin_log` VALUES (129, 3, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555292887, 1555292887);
INSERT INTO `admin_log` VALUES (130, 3, 'http://www.firefly.test:81/admin/user/admin.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'admin', '修改[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555292953, 1555292953);
INSERT INTO `admin_log` VALUES (131, 3, 'http://www.firefly.test:81/admin/user/admin.html', 'http://www.firefly.test:81/admin/form/upload.html', 'admin', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555292981, 1555292981);
INSERT INTO `admin_log` VALUES (132, 3, 'http://www.firefly.test:81/admin/user/admin.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'admin', '修改[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555292983, 1555292983);
INSERT INTO `admin_log` VALUES (133, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'menu', '添加[id:26]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555293558, 1555293558);
INSERT INTO `admin_log` VALUES (134, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'menu', '添加[id:27]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555295137, 1555295137);
INSERT INTO `admin_log` VALUES (135, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'menu', '添加[id:28]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555296293, 1555296293);
INSERT INTO `admin_log` VALUES (136, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'menu', '修改[id:28]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555296326, 1555296326);
INSERT INTO `admin_log` VALUES (137, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'menu', '添加[id:29]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555296521, 1555296521);
INSERT INTO `admin_log` VALUES (138, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'menu', '修改[id:29]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555296534, 1555296534);
INSERT INTO `admin_log` VALUES (139, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'system_config', '添加[id:22]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555307317, 1555307317);
INSERT INTO `admin_log` VALUES (140, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'system_config', '添加[id:23]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555307454, 1555307454);
INSERT INTO `admin_log` VALUES (141, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:19]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555307870, 1555307870);
INSERT INTO `admin_log` VALUES (142, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:19]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555307891, 1555307891);
INSERT INTO `admin_log` VALUES (143, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'system_config', '添加[id:24]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555307923, 1555307923);
INSERT INTO `admin_log` VALUES (144, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:21]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555309901, 1555309901);
INSERT INTO `admin_log` VALUES (145, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:15]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555309919, 1555309919);
INSERT INTO `admin_log` VALUES (146, 3, 'http://www.firefly.test:81/admin/user/user.html', 'http://www.firefly.test:81/admin/form/upload.html', 'user', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555309995, 1555309995);
INSERT INTO `admin_log` VALUES (147, 3, 'http://www.firefly.test:81/admin/user/user.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'user', '添加[id:1]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555310034, 1555310034);
INSERT INTO `admin_log` VALUES (148, 3, 'http://www.firefly.test:81/admin/user/user.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'user', '修改[id:1]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555310083, 1555310083);
INSERT INTO `admin_log` VALUES (149, 3, 'http://www.firefly.test:81/admin/user/admin.html', 'http://www.firefly.test:81/admin/form/upload.html', 'admin', '上传失败上传文件后缀不允许', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555323701, 1555323701);
INSERT INTO `admin_log` VALUES (150, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:15]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555323797, 1555323797);
INSERT INTO `admin_log` VALUES (151, 3, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555380652, 1555380652);
INSERT INTO `admin_log` VALUES (152, 3, 'http://www.firefly.test:81/admin/user/admin.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'admin', '修改[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555384736, 1555384736);
INSERT INTO `admin_log` VALUES (153, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:15]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555399851, 1555399851);
INSERT INTO `admin_log` VALUES (154, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:15]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555401973, 1555401973);
INSERT INTO `admin_log` VALUES (155, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', 'Message has been sent', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 6, 1, 0, 1555405186, 1555405186);
INSERT INTO `admin_log` VALUES (156, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', '添加[id:1]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555405186, 1555405186);
INSERT INTO `admin_log` VALUES (157, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', 'Message has been sent', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 6, 1, 0, 1555405210, 1555405210);
INSERT INTO `admin_log` VALUES (158, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', '添加[id:2]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555405210, 1555405210);
INSERT INTO `admin_log` VALUES (159, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555414237, 1555414237);
INSERT INTO `admin_log` VALUES (160, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555419936, 1555419936);
INSERT INTO `admin_log` VALUES (161, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555419948, 1555419948);
INSERT INTO `admin_log` VALUES (162, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555419948, 1555419948);
INSERT INTO `admin_log` VALUES (163, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420272, 1555420272);
INSERT INTO `admin_log` VALUES (164, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420272, 1555420272);
INSERT INTO `admin_log` VALUES (165, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420272, 1555420272);
INSERT INTO `admin_log` VALUES (166, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420432, 1555420432);
INSERT INTO `admin_log` VALUES (167, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420432, 1555420432);
INSERT INTO `admin_log` VALUES (168, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420432, 1555420432);
INSERT INTO `admin_log` VALUES (169, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420579, 1555420579);
INSERT INTO `admin_log` VALUES (170, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420587, 1555420587);
INSERT INTO `admin_log` VALUES (171, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420625, 1555420625);
INSERT INTO `admin_log` VALUES (172, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420641, 1555420641);
INSERT INTO `admin_log` VALUES (173, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420650, 1555420650);
INSERT INTO `admin_log` VALUES (174, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420707, 1555420707);
INSERT INTO `admin_log` VALUES (175, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420707, 1555420707);
INSERT INTO `admin_log` VALUES (176, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420707, 1555420707);
INSERT INTO `admin_log` VALUES (177, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420726, 1555420726);
INSERT INTO `admin_log` VALUES (178, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420726, 1555420726);
INSERT INTO `admin_log` VALUES (179, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420726, 1555420726);
INSERT INTO `admin_log` VALUES (180, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420752, 1555420752);
INSERT INTO `admin_log` VALUES (181, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555420811, 1555420811);
INSERT INTO `admin_log` VALUES (182, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/event_add.html', 'email', 'Message has been sent', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 6, 1, 0, 1555425411, 1555425411);
INSERT INTO `admin_log` VALUES (183, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/event_add.html', 'email', '添加[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555425411, 1555425411);
INSERT INTO `admin_log` VALUES (184, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555425507, 1555425507);
INSERT INTO `admin_log` VALUES (185, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/event_edit.html', 'email', '修改[id:3]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555425515, 1555425515);
INSERT INTO `admin_log` VALUES (186, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555425535, 1555425535);
INSERT INTO `admin_log` VALUES (187, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/event_add.html', 'email', 'Message has been sent', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 6, 1, 0, 1555425539, 1555425539);
INSERT INTO `admin_log` VALUES (188, 3, 'http://www.firefly.test/admin/mail/email.html', 'http://www.firefly.test/admin/form/event_add.html', 'email', '添加[id:4]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555425539, 1555425539);
INSERT INTO `admin_log` VALUES (189, 3, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555467131, 1555467131);
INSERT INTO `admin_log` VALUES (190, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555467165, 1555467165);
INSERT INTO `admin_log` VALUES (191, 3, 'http://www.firefly.test:81/admin/user/user.html', 'http://www.firefly.test:81/admin/form/upload.html', 'user', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555467480, 1555467480);
INSERT INTO `admin_log` VALUES (192, 3, 'http://www.firefly.test:81/admin/user/user.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'user', '添加[id:2]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555467706, 1555467706);
INSERT INTO `admin_log` VALUES (193, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', 'Message has been sent', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 6, 1, 0, 1555468035, 1555468035);
INSERT INTO `admin_log` VALUES (194, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', '添加[id:5]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555468035, 1555468035);
INSERT INTO `admin_log` VALUES (195, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:21]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555470308, 1555470308);
INSERT INTO `admin_log` VALUES (196, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:17]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555470325, 1555470325);
INSERT INTO `admin_log` VALUES (197, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:19]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555470337, 1555470337);
INSERT INTO `admin_log` VALUES (198, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:24]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555470351, 1555470351);
INSERT INTO `admin_log` VALUES (199, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555470376, 1555470376);
INSERT INTO `admin_log` VALUES (200, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/upload.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555470386, 1555470386);
INSERT INTO `admin_log` VALUES (201, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:17]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555470510, 1555470510);
INSERT INTO `admin_log` VALUES (202, 3, 'http://www.firefly.test:81/admin/system/system_config.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'system_config', '修改[id:19]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555470668, 1555470668);
INSERT INTO `admin_log` VALUES (203, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', 'Message has been sent', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 6, 1, 0, 1555470710, 1555470710);
INSERT INTO `admin_log` VALUES (204, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', '添加[id:6]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555470710, 1555470710);
INSERT INTO `admin_log` VALUES (205, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555470965, 1555470965);
INSERT INTO `admin_log` VALUES (206, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', 'Message has been sent', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 6, 1, 0, 1555471209, 1555471209);
INSERT INTO `admin_log` VALUES (207, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', '添加[id:7]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555471209, 1555471209);
INSERT INTO `admin_log` VALUES (208, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/wangeditor_image.html', 'email', '上传成功!', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 7, 1, 0, 1555471291, 1555471291);
INSERT INTO `admin_log` VALUES (209, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', 'Message has been sent', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 6, 1, 0, 1555471295, 1555471295);
INSERT INTO `admin_log` VALUES (210, 3, 'http://www.firefly.test:81/admin/mail/email.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'email', '添加[id:8]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555471295, 1555471295);
INSERT INTO `admin_log` VALUES (211, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'menu', '添加[id:30]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555494463, 1555494463);
INSERT INTO `admin_log` VALUES (212, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_add.html', 'menu', '添加[id:31]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555495763, 1555495763);
INSERT INTO `admin_log` VALUES (213, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555506472, 1555506472);
INSERT INTO `admin_log` VALUES (214, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:28]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555506903, 1555506903);
INSERT INTO `admin_log` VALUES (215, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:32]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555509944, 1555509944);
INSERT INTO `admin_log` VALUES (216, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:33]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555510071, 1555510071);
INSERT INTO `admin_log` VALUES (217, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:33]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555510091, 1555510091);
INSERT INTO `admin_log` VALUES (218, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:34]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555511265, 1555511265);
INSERT INTO `admin_log` VALUES (219, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:35]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555511359, 1555511359);
INSERT INTO `admin_log` VALUES (220, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:36]数据项成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555512849, 1555512849);
INSERT INTO `admin_log` VALUES (221, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:37]数据项成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555513140, 1555513140);
INSERT INTO `admin_log` VALUES (222, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:38]数据项成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555513182, 1555513182);
INSERT INTO `admin_log` VALUES (223, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_add.html', 'menu', '添加[id:39]数据项成功', '127.0.0.1', '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 0, 1, 0, 1555513221, 1555513221);
INSERT INTO `admin_log` VALUES (224, 3, 'http://www.firefly.test/admin/system/menu.html', 'http://www.firefly.test/admin/form/event_edit.html', 'menu', '修改[id:39]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555545176, 1555545176);
INSERT INTO `admin_log` VALUES (225, 3, 'http://www.firefly.test/login.html', 'http://www.firefly.test/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555821942, 1555821942);
INSERT INTO `admin_log` VALUES (226, 3, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555923236, 1555923236);
INSERT INTO `admin_log` VALUES (227, 3, 'http://www.firefly.test:81/admin/index/index.html', 'http://www.firefly.test:81/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1555926724, 1555926724);
INSERT INTO `admin_log` VALUES (228, 3, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555926727, 1555926727);
INSERT INTO `admin_log` VALUES (229, 3, 'http://www.firefly.test:81/admin/user/admin.html', 'http://www.firefly.test:81/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1555926744, 1555926744);
INSERT INTO `admin_log` VALUES (230, 4, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555926750, 1555926750);
INSERT INTO `admin_log` VALUES (231, 4, 'http://www.firefly.test:81/admin/index/index.html', 'http://www.firefly.test:81/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1555927215, 1555927215);
INSERT INTO `admin_log` VALUES (232, 3, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555927223, 1555927223);
INSERT INTO `admin_log` VALUES (233, 3, 'http://www.firefly.test:81/admin/index/index.html', 'http://www.firefly.test:81/admin/login/logout.html', 'admin', '退出成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 5, 1, 0, 1555927277, 1555927277);
INSERT INTO `admin_log` VALUES (234, 3, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1555927281, 1555927281);
INSERT INTO `admin_log` VALUES (235, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'menu', '修改[id:6]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555927896, 1555927896);
INSERT INTO `admin_log` VALUES (236, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'menu', '修改[id:5]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555927911, 1555927911);
INSERT INTO `admin_log` VALUES (237, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'menu', '修改[id:6]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555927921, 1555927921);
INSERT INTO `admin_log` VALUES (238, 3, 'http://www.firefly.test:81/admin/system/menu.html', 'http://www.firefly.test:81/admin/form/event_edit.html', 'menu', '修改[id:5]数据项成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 2, 1, 0, 1555927927, 1555927927);

-- ----------------------------
-- Table structure for attachment
-- ----------------------------
DROP TABLE IF EXISTS `attachment`;
CREATE TABLE `attachment`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `admin_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '原文件名',
  `save_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '新文件名',
  `mime` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件类型(mime)',
  `ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件后缀',
  `size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件大小(Byte)',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '物理存储路径',
  `net_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '网络路径',
  `md5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件md5',
  `sha1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件sha1',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of attachment
-- ----------------------------
INSERT INTO `attachment` VALUES (10, 3, '30ced021cd5346d5b1393e4cb4bf4252!400x400.jpeg', 'ecc8aa28bffc44a22b844950e72cfbb7.jpeg', 'image/jpeg', 'jpeg', '27349', './uploads/email/20190416/ecc8aa28bffc44a22b844950e72cfbb7.jpeg', '/uploads/email/20190416/ecc8aa28bffc44a22b844950e72cfbb7.jpeg', 'f260487a5d9f9f8fd03e5e0121bf4495', '820e408abe7093ffcfcbd0ff8a54d91d686e8c6c', 1, 0, 1555420706, 1555420706);
INSERT INTO `attachment` VALUES (11, 3, 'c21bf361d8e54242a01c666008c77eeb!400x400.jpeg', '478a3607465f69d7b7369eb5dd4055a2.jpeg', 'image/jpeg', 'jpeg', '45044', './uploads/email/20190416/478a3607465f69d7b7369eb5dd4055a2.jpeg', '/uploads/email/20190416/478a3607465f69d7b7369eb5dd4055a2.jpeg', 'f4ca9eb9d4f7f22f2c5783d2133ec5ec', '3c70c61e601b0d253b97b0ddedd1c0617ba99655', 1, 0, 1555420707, 1555420707);
INSERT INTO `attachment` VALUES (12, 3, 'f5e951fe320e46009afe136c2955015f!400x400.jpeg', 'cde05b8cce88a039043731ee19ae15fb.jpeg', 'image/jpeg', 'jpeg', '24669', './uploads/email/20190416/cde05b8cce88a039043731ee19ae15fb.jpeg', '/uploads/email/20190416/cde05b8cce88a039043731ee19ae15fb.jpeg', 'bf75ccd7246c5127a73b93e71b234eaa', 'deda70e44fe362b3914a539af73446aaf24dc9cc', 1, 0, 1555420707, 1555420707);
INSERT INTO `attachment` VALUES (13, 3, '0ab1bb678e5544b6965d329a30d606c0!400x400.jpeg', '1cd27305f2ddc9fcc0e27f3af66464d6.jpeg', 'image/jpeg', 'jpeg', '38357', './uploads/email/20190417/1cd27305f2ddc9fcc0e27f3af66464d6.jpeg', '/uploads/email/20190417/1cd27305f2ddc9fcc0e27f3af66464d6.jpeg', '1a459f4b176c0f8664621b265faf428e', 'f1cb4330a72fc177158491a79c8e9ce77a6b3246', 1, 0, 1555467165, 1555467165);
INSERT INTO `attachment` VALUES (14, 3, '1 - 副本.jpeg', 'dc5536ec6630065ab55b23ddd8612c45.jpeg', 'image/jpeg', 'jpeg', '33809', './uploads/user/20190417/dc5536ec6630065ab55b23ddd8612c45.jpeg', '/uploads/user/20190417/dc5536ec6630065ab55b23ddd8612c45.jpeg', 'db4471ff0201f51aed09529a4ec57f19', 'f66fedac728398e68c16a0a540c5e5f3b1363791', 1, 0, 1555467480, 1555467480);
INSERT INTO `attachment` VALUES (15, 3, '3b57dae5eb664c94bfc579076b7106e8!400x400.jpeg', '585465febe6a8be1378c958cadc0a1d7.jpeg', 'image/jpeg', 'jpeg', '34032', './uploads/email/20190417/585465febe6a8be1378c958cadc0a1d7.jpeg', '/uploads/email/20190417/585465febe6a8be1378c958cadc0a1d7.jpeg', 'bee842d692718bb4abffcf2bf9a29796', '786e79e67b17ac345e5c31ff1cf43c01561a89f3', 1, 0, 1555470386, 1555470386);
INSERT INTO `attachment` VALUES (16, 3, '4ed8396a1ffe4576aabde08cdabd3abb!400x400.jpeg', 'ea46d65dba63b85037587d09ffbe343f.jpeg', 'image/jpeg', 'jpeg', '33315', './uploads/email/20190417/ea46d65dba63b85037587d09ffbe343f.jpeg', '/uploads/email/20190417/ea46d65dba63b85037587d09ffbe343f.jpeg', 'a22a6b8f4ba841317df7fdbe275951ae', 'a2e2df7e0e7be3a6d064416da9a5cffe76632e90', 1, 0, 1555471290, 1555471290);

-- ----------------------------
-- Table structure for email
-- ----------------------------
DROP TABLE IF EXISTS `email`;
CREATE TABLE `email`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `admin_id` bigint(20) NOT NULL COMMENT '管理员id',
  `to` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '收信人',
  `from` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '寄信人',
  `subject` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `context` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '纯文本',
  `email_files` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '附件',
  `email_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '邮件类型[0:HTML,1:TXT]',
  `is_success` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否成功[0:失败,1:成功]',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of email
-- ----------------------------
INSERT INTO `email` VALUES (1, 1, 3, 'snoopyshenlu@163.com', NULL, '你好-测试邮件', '测试邮件，主页内容', '纯文本消息', './uploads/email/20190416/8d1a330f8993b140a9614d9d9daa82c1.jpeg', 0, 1, 1, 0, 1555405177, 1555405177);
INSERT INTO `email` VALUES (2, 1, 3, 'snoopyshenlu@163.com', NULL, '你好-测试邮件', '测试邮件，主页内容', '纯文本消息', './uploads/email/20190416/8d1a330f8993b140a9614d9d9daa82c1.jpeg', 0, 1, 1, 0, 1555405177, 1555405177);
INSERT INTO `email` VALUES (3, 1, 4, 'snoopyshenlu@163.com', NULL, '富文本测试', '<h1>富文本测试</h1><p><span style=\"font-weight: bold;\">富文本测试</span><br></p><blockquote>富文本测试</blockquote><p><span style=\"color: rgb(70, 172, 200);\">富文本测试</span></p><p><img src=\"/uploads/email/20190416/ecc8aa28bffc44a22b844950e72cfbb7.jpeg\" style=\"max-width:30%;\"><span style=\"color: rgb(70, 172, 200);\"><br></span></p>', '', '', 0, 1, 1, 0, 1555425344, 1555425515);
INSERT INTO `email` VALUES (4, 1, 3, 'snoopyshenlu@163.com', NULL, '富文本图片测试', '<p><img src=\"/uploads/email/20190416/ecc8aa28bffc44a22b844950e72cfbb7.jpeg\" style=\"max-width:100%;\"><br></p>', '', '', 0, 1, 1, 0, 1555425519, 1555425519);
INSERT INTO `email` VALUES (5, 2, 3, '1445154365@qq.com', NULL, 'Test Email', '<p style=\"text-align: center;\"><img src=\"https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=3523617831,1288544462&amp;fm=27&amp;gp=0.jpg\" style=\"max-width:100%;\"><br></p><h1 style=\"text-align: center;\"><span style=\"font-weight: bold;\">Test Message: <span style=\"font-style: italic;\">this is a test email to you!</span></span><br></h1>', '', './uploads/user/20190417/dc5536ec6630065ab55b23ddd8612c45.jpeg', 0, 1, 1, 0, 1555467827, 1555467827);
INSERT INTO `email` VALUES (6, 1, 3, 'snoopyshenlu@163.com', NULL, '阿里云邮箱测试', '<p><img src=\"/uploads/user/20190417/dc5536ec6630065ab55b23ddd8612c45.jpeg\" style=\"max-width:100%;\">111<br></p>', '', './uploads/email/20190417/585465febe6a8be1378c958cadc0a1d7.jpeg', 0, 1, 1, 0, 1555470358, 1555470358);
INSERT INTO `email` VALUES (7, 1, 3, 'snoopyshenlu@163.com', NULL, '嘿嘿', '<p><img src=\"/uploads/email/20190417/585465febe6a8be1378c958cadc0a1d7.jpeg\" style=\"max-width:100%;\"><br></p>', '', '', 0, 1, 1, 0, 1555470954, 1555470954);
INSERT INTO `email` VALUES (8, 1, 3, 'snoopyshenlu@163.com', NULL, '阿里云，本地图片测试', '<h1>你好，hello ！</h1><p><img src=\"/uploads/email/20190417/ea46d65dba63b85037587d09ffbe343f.jpeg\" style=\"max-width:100%;\"><br></p>', '', '', 0, 1, 1, 0, 1555471215, 1555471215);

-- ----------------------------
-- Table structure for file
-- ----------------------------
DROP TABLE IF EXISTS `file`;
CREATE TABLE `file`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `folder_id` bigint(20) NULL DEFAULT NULL COMMENT '目录编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '原文件名',
  `save_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '新文件名',
  `mime` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件类型(mime)',
  `ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件后缀',
  `size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件大小(Byte)',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '物理存储路径',
  `net_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '网络路径',
  `user_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户路径(关联folder表)',
  `old_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '上一次路径(关联folder表)',
  `description_context` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件描述',
  `is_encrypt` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否加密[0:没加密,1:加密]',
  `md5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件md5',
  `sha1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件sha1',
  `share_frequency` int(11) NOT NULL DEFAULT 0 COMMENT '分享次数',
  `down_frequency` int(11) NOT NULL DEFAULT 0 COMMENT '下载次数',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for folder
-- ----------------------------
DROP TABLE IF EXISTS `folder`;
CREATE TABLE `folder`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `pid` bigint(20) NULL DEFAULT NULL COMMENT '父编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '目录名称',
  `path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '路径(/开头)',
  `remark_context` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `pid` bigint(20) NULL DEFAULT NULL COMMENT '父菜单编号',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '规则',
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '附加属性',
  `menu_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '菜单类型[0:非菜单,1:链接,2:图片,3:文字,4:图标,5:自定义]',
  `weigh` bigint(20) NOT NULL DEFAULT 1 COMMENT '权重',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, 0, NULL, '左侧导航栏', NULL, NULL, 1, 1, 1, 0, NULL, 1554610743);
INSERT INTO `menu` VALUES (2, 0, NULL, '顶部左侧导航', NULL, NULL, 1, 2, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (3, 0, NULL, '顶部右侧导航', NULL, NULL, 1, 3, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (4, 1, 'layui-icon layui-icon-console', '控制台', '', '', 1, 4, 1, 0, NULL, 1555239541);
INSERT INTO `menu` VALUES (5, 4, 'layui-icon layui-icon-home', '主页一', 'admin/index/index', '', 1, 5, 1, 0, NULL, 1555927927);
INSERT INTO `menu` VALUES (6, 4, 'layui-icon layui-icon-theme', '主页二', 'admin/index/index2', '', 1, 6, 1, 0, NULL, 1555927920);
INSERT INTO `menu` VALUES (7, 2, NULL, '顶部1', NULL, NULL, 1, 7, 1, 0, NULL, 1554735770);
INSERT INTO `menu` VALUES (8, 2, NULL, '顶部2', NULL, NULL, 1, 8, 1, 0, NULL, 1554735770);
INSERT INTO `menu` VALUES (9, 8, NULL, '顶部2 - 1', NULL, NULL, 1, 9, 1, 0, NULL, 1554735770);
INSERT INTO `menu` VALUES (10, 3, 'layui-icon layui-icon-set', '系统设置', '', NULL, 4, 14, 1, 0, NULL, 1554735770);
INSERT INTO `menu` VALUES (11, 7, NULL, '顶部1 - 1', NULL, NULL, 1, 11, 1, 0, NULL, 1554735770);
INSERT INTO `menu` VALUES (12, 10, NULL, '菜单配置', 'admin/system/menu', NULL, 1, 12, 1, 0, NULL, 1554735770);
INSERT INTO `menu` VALUES (13, 10, '', '系统参数', 'admin/system/system_config', '', 1, 13, 1, 0, NULL, 1555073635);
INSERT INTO `menu` VALUES (14, 3, 'layui-icon layui-icon-refresh', '刷新', '', 'fiy-loop', 4, 10, 1, 0, NULL, 1554735770);
INSERT INTO `menu` VALUES (15, 14, NULL, '刷新页面', NULL, 'fiy-reload', 1, 15, 1, 0, NULL, 1554735770);
INSERT INTO `menu` VALUES (16, 14, '', '重置表格', '', 'table_render', 1, 16, 1, 0, NULL, 1555073609);
INSERT INTO `menu` VALUES (17, 1, 'layui-icon layui-icon-theme', 'test', '', '', 1, 17, 0, 1, 2019, 1555073588);
INSERT INTO `menu` VALUES (18, 1, 'layui-icon layui-icon-snowflake', 'test2', '', '', 1, 18, 0, 1, 2019, 1555073588);
INSERT INTO `menu` VALUES (19, 1, 'layui-icon layui-icon-home', 'test3', 'aaa', '111', 4, 19, 0, 1, 2019, 1555073588);
INSERT INTO `menu` VALUES (20, 1, 'layui-icon layui-icon-shrink-right', 'test', '', '', 1, 20, 0, 1, 2019, 1555073588);
INSERT INTO `menu` VALUES (21, 1, 'layui-icon layui-icon-shrink-right', 'test', '', '', 1, 20, 0, 1, 2019, 1555073588);
INSERT INTO `menu` VALUES (22, 1, 'layui-icon layui-icon-template-1', '111', '', '', 1, 22, 0, 1, 1554907747, 1555073588);
INSERT INTO `menu` VALUES (23, 1, 'layui-icon layui-icon-user', '用户管理', '', '', 0, 23, 1, 0, 1555239327, 1555239518);
INSERT INTO `menu` VALUES (24, 23, 'layui-icon layui-icon-username', '管理员用户', 'admin/user/admin', '', 1, 24, 1, 0, 1555239468, 1555250515);
INSERT INTO `menu` VALUES (25, 23, 'fa fa-user-o', '普通用户管理', 'admin/user/user', '', 1, 25, 1, 0, 1555250859, 1555250859);
INSERT INTO `menu` VALUES (26, 23, 'fa fa-file-text-o', '管理员日志', 'admin/user/admin_log', '', 1, 26, 1, 0, 1555293496, 1555293496);
INSERT INTO `menu` VALUES (27, 23, 'layui-icon layui-icon-file-b', '用户日志', 'admin/user/user_log', '', 1, 27, 1, 0, 1555295117, 1555295117);
INSERT INTO `menu` VALUES (28, 1, 'fa fa-envelope-o', '信箱管理', '', '', 0, 28, 1, 0, 1555296110, 1555506903);
INSERT INTO `menu` VALUES (29, 28, 'fa fa-envelope-square', '电子邮箱', 'admin/mail/email', '', 1, 29, 1, 0, 1555296445, 1555296533);
INSERT INTO `menu` VALUES (30, 28, 'fa fa-envelope-open-o', '站内信箱', 'admin/mail/site_mail', '', 1, 30, 1, 0, 1555494420, 1555494420);
INSERT INTO `menu` VALUES (31, 28, 'fa fa-envelope', '系统信件', 'admin/mail/site_system_mail', '', 1, 31, 1, 0, 1555495719, 1555495719);
INSERT INTO `menu` VALUES (32, 1, 'fa fa-file-o', '文件管理', '', '', 0, 32, 1, 0, 1555509869, 1555509869);
INSERT INTO `menu` VALUES (33, 32, 'layui-icon layui-icon-find-fill', '后台文件', 'admin/file/attachment', '', 1, 33, 1, 0, 1555509966, 1555510090);
INSERT INTO `menu` VALUES (34, 32, 'fa fa-folder-o', '目录管理', 'admin/file/folder', '', 1, 34, 1, 0, 1555511232, 1555511232);
INSERT INTO `menu` VALUES (35, 32, 'layui-icon layui-icon-file-b', '用户文件', 'admin/file/file', '', 1, 35, 1, 0, 1555511340, 1555511340);
INSERT INTO `menu` VALUES (36, 32, 'layui-icon layui-icon-download-circle', '上传下载', 'admin/file/up_down', '', 1, 36, 1, 0, 1555512827, 1555512827);
INSERT INTO `menu` VALUES (37, 1, 'fa fa-book', '分享管理', '', '', 1, 37, 1, 0, 1555513009, 1555513009);
INSERT INTO `menu` VALUES (38, 37, 'layui-icon layui-icon-share', '用户分享', 'admin/share/share', '', 1, 38, 1, 0, 1555513152, 1555513152);
INSERT INTO `menu` VALUES (39, 37, 'fa fa-commenting', '分享评论', 'admin/share/share_comment', '', 1, 39, 1, 0, 1555513185, 1555545176);

-- ----------------------------
-- Table structure for share
-- ----------------------------
DROP TABLE IF EXISTS `share`;
CREATE TABLE `share`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `file_ids` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '文件id集合',
  `subject` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '主题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `share_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '分享类型[0:完全公开,1:加密公开,2:期限公开,3:期限加密公开,4:次数公开,5:次数加密公开,6:有期限次数公开,7:有期限次数加密公开]',
  `share_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分享密码',
  `expire_time` int(10) NULL DEFAULT NULL COMMENT '到期时间',
  `frquency` int(11) NULL DEFAULT NULL COMMENT '使用次数',
  `use_frequency` int(11) NULL DEFAULT NULL COMMENT '已使用次数',
  `allow_comment` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否允许评论[0:不允许,1:允许]',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'ip地址',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '实际地理位置',
  `show_location` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否显示地点[0:不显示,1:显示]',
  `custom_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户自定义地理位置',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '代理信息',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for share_comment
-- ----------------------------
DROP TABLE IF EXISTS `share_comment`;
CREATE TABLE `share_comment`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `pid` bigint(20) NULL DEFAULT NULL COMMENT '父编号',
  `share_id` bigint(20) NULL DEFAULT NULL COMMENT '分享编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `context` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '评论内容',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'ip地址',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '实际地理位置',
  `show_location` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否显示地点[0:不显示,1:显示]',
  `custom_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户自定义地理位置',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '客户端信息',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for site_mail
-- ----------------------------
DROP TABLE IF EXISTS `site_mail`;
CREATE TABLE `site_mail`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `to_id` bigint(20) NOT NULL COMMENT '收件人id',
  `from_id` bigint(20) NOT NULL COMMENT '发件人id',
  `subject` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '主题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `context` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '纯文本',
  `is_system` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否系统邮件[0:否,1:是]',
  `is_read` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否已读[0:未读,1:已读]',
  `email_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '邮件类型[0:HTML,1:TXT]',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for site_system_mail
-- ----------------------------
DROP TABLE IF EXISTS `site_system_mail`;
CREATE TABLE `site_system_mail`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `site_mail_id` bigint(20) NULL DEFAULT NULL COMMENT '站内信id',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户id',
  `is_read` tinyint(1) NULL DEFAULT NULL COMMENT '是否已读[0:未读,1:已读]',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for system_config
-- ----------------------------
DROP TABLE IF EXISTS `system_config`;
CREATE TABLE `system_config`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `value_context` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '值',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片',
  `config_type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '类型[0:站点,1:邮箱]',
  `remark_context` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `weigh` bigint(20) NOT NULL DEFAULT 1 COMMENT '权重',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_config
-- ----------------------------
INSERT INTO `system_config` VALUES (4, '程序名称', 'program_name', 'FireFlyCms', '', 0, '', 1, 0, 1, 1555156164, 1555164279);
INSERT INTO `system_config` VALUES (5, '程序版本号', 'program_version', 'v1.0', '', 0, '', 1, 0, 5, 1555164086, 1555164086);
INSERT INTO `system_config` VALUES (6, '网站图标', 'website_icon', '/favicon.png', '', 0, '浏览器小图标', 1, 0, 6, 1555164159, 1555164217);
INSERT INTO `system_config` VALUES (7, '站点标题', 'website_name', '萤火云后台管理系统', '', 0, '', 1, 0, 7, 1555164220, 1555166303);
INSERT INTO `system_config` VALUES (8, '网站域名', 'website_domain', '', '', 0, '', 1, 0, 8, 1555164290, 1555164290);
INSERT INTO `system_config` VALUES (9, '版权信息', 'website_copyright', '© 2019 FireFlyCms', '', 0, '', 1, 0, 9, 1555164918, 1555215997);
INSERT INTO `system_config` VALUES (10, 'ICP备案号', 'beian_icp', '', '', 0, 'icp备案号', 1, 0, 10, 1555165010, 1555165010);
INSERT INTO `system_config` VALUES (11, '公安备案号', 'beian_police', '', '', 0, '公安备案号', 1, 0, 11, 1555165050, 1555165050);
INSERT INTO `system_config` VALUES (12, '站点描述', 'website_description', '萤火虫cms,基于thinkphp5.1的后台内容管理系统', '', 0, '站点描述', 1, 0, 12, 1555165072, 1555165289);
INSERT INTO `system_config` VALUES (13, '站点关键字', 'website_keywords', 'cms|tp5.1|thinkphp5.1|后台管理系统|firefly|萤火虫|萤火云', '', 0, '站点关键字', 1, 0, 13, 1555165192, 1555165280);
INSERT INTO `system_config` VALUES (14, '文件上传大小', 'upload_maxsize', '10485760', '', 0, '单位B(byte):[1T=1024G,1G=1025M,1M=1024B]', 1, 0, 14, 1555165300, 1555165300);
INSERT INTO `system_config` VALUES (15, '文件上传后缀', 'upload_ext', 'jpeg,jpg,png,gif,bmp,ico,txt,rar,doc,docx,ppt,pptx,xls,xlsx,zip,pdf,sql', '', 0, '允许上传的文件后缀', 1, 0, 15, 1555165461, 1555401972);
INSERT INTO `system_config` VALUES (16, '站点安装时间', 'website_createtime', '', '', 0, '此后台管理系统安装完毕后生成，同时会在应用更目录生成install.lock文件与超级管理员用户。', 1, 0, 16, 1555165572, 1555165572);
INSERT INTO `system_config` VALUES (17, 'smtp服务器', 'email_smtp', 'smtp.aliyun.com', '', 1, '例如smtp.163.com', 1, 0, 17, 1555165640, 1555470510);
INSERT INTO `system_config` VALUES (18, 'smtp端口号', 'email_port', '465', '', 1, '常用端口为465和25，目前一般为465。', 1, 0, 18, 1555165685, 1555165685);
INSERT INTO `system_config` VALUES (19, '发件人邮箱', 'email_username', 'aroad.xyz@aliyun.com', '', 1, '发件人邮箱地址，例如 xxxxx@qq.com 或者去掉@qq.com', 1, 0, 19, 1555165735, 1555470668);
INSERT INTO `system_config` VALUES (20, '发件人昵称', 'email_nick', '萤火云', '', 1, '发件人昵称', 1, 0, 20, 1555165788, 1555165788);
INSERT INTO `system_config` VALUES (21, '邮箱密码', 'email_password', 'Shenlu123..', '', 1, '一般不是邮箱直接登录密码，而是授权码。zcmvszcvmscdbabb', 1, 0, 21, 1555165821, 1555470308);
INSERT INTO `system_config` VALUES (22, 'smtp安全类型', 'email_secure', 'ssl', '', 1, 'smtp安全类型,一般有tls和ssl', 1, 0, 22, 1555307218, 1555307218);
INSERT INTO `system_config` VALUES (23, '邮箱加密', 'email_auth', 'true', '', 1, '邮箱加密，填写true或false，或1或0', 1, 0, 23, 1555307396, 1555307396);
INSERT INTO `system_config` VALUES (24, '邮箱地址', 'email_url', 'aroad.xyz@aliyun.com', '', 1, '邮箱地址', 1, 0, 24, 1555307893, 1555470351);

-- ----------------------------
-- Table structure for up_down
-- ----------------------------
DROP TABLE IF EXISTS `up_down`;
CREATE TABLE `up_down`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `file_id` bigint(20) NULL DEFAULT NULL COMMENT '文件编号',
  `up_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类型[0:上传,1:下载]',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL COMMENT '是否删除[0:未删除,1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户名',
  `nickname` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '密码',
  `salt` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '密码盐',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像',
  `gender` tinyint(1) NOT NULL DEFAULT 0 COMMENT '性别[0:保密,1:女,2:男]',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `tel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '电话',
  `role` tinyint(2) NOT NULL DEFAULT 0 COMMENT '权限[0:普通用户,1:高级用户]',
  `qq` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'qq',
  `born` date NULL DEFAULT NULL COMMENT '出生年月',
  `sign_context` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '签名',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述',
  `score` int(10) NOT NULL DEFAULT 0 COMMENT '积分',
  `level` tinyint(4) NOT NULL DEFAULT 0 COMMENT '等级',
  `decorate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '装饰',
  `file_password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件密码',
  `total_size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '盘空间(单位Byte)',
  `use_size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '已使用盘空间(单位Byte)',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '令牌',
  `login_fail` tinyint(2) NOT NULL DEFAULT 0 COMMENT '登录失败次数',
  `login_day` int(10) NOT NULL DEFAULT 0 COMMENT '连续登录天数',
  `login_max_day` int(10) NOT NULL DEFAULT 0 COMMENT '最多连续登录天数',
  `login_total_day` int(10) NOT NULL DEFAULT 0 COMMENT '总登录天数',
  `vcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '验证码',
  `invite_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邀请码',
  `reg_invite_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '受邀码',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `weigh` bigint(20) NOT NULL DEFAULT 1 COMMENT '权重',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '注册时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  `signin_day` int(10) NOT NULL DEFAULT 0 COMMENT '当前连续签到天数',
  `siginin_max_day` int(10) NOT NULL DEFAULT 0 COMMENT '最多一次连续签到天数',
  `signin_total_day` int(10) NOT NULL DEFAULT 0 COMMENT '总签到天数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'user1', '测试用户1', '92e18489e5b8cd01abd29771d3d05f513b8ce7e8', '6pXv/', '/uploads/user/20190415/13e091e0bcbc0aabb49c2b42efbf13b8.jpeg', 1, 'snoopyshenlu@163.com', '', 0, '1479221500', '2019-04-15', '', '', 0, 0, '', '', '', '', NULL, 0, 0, 0, 0, NULL, '', '', 1, 0, 1, 1555309968, 1555310083, 0, 0, 0);
INSERT INTO `user` VALUES (2, 'yl-198', '199-8', 'cd4b7d5602a57e8f89df6a13f2c2dd98b92cbf4f', 'R^zOK', '/uploads/user/20190417/dc5536ec6630065ab55b23ddd8612c45.jpeg', 2, '1445154365@qq.com', '', 0, '1445154365', '2019-04-17', '', '', 0, 0, '', '', '', '', NULL, 0, 0, 0, 0, NULL, '', '', 1, 0, 2, 1555467681, 1555467681, 0, 0, 0);

-- ----------------------------
-- Table structure for user_log
-- ----------------------------
DROP TABLE IF EXISTS `user_log`;
CREATE TABLE `user_log`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '来源链接',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '当前链接',
  `table` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '表名',
  `info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '详情',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'ip地址',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '地理位置',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '客户端信息',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态[0:隐藏,1:显示]',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除[0:未删除,1:已删除]',
  `user_log_type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '日志类型[0:增,1:删,2:改,3:查,4:登录,5:退出]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近更新',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
