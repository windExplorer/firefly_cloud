/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost:3306
 Source Schema         : book_simple_cloud

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : 65001

 Date: 01/04/2019 18:17:08
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
  `gender` tinyint(1) NULL DEFAULT NULL COMMENT '性别[0:保密 1:女 2:.男]',
  `born` date NULL DEFAULT NULL COMMENT '出生年月',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `tel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '电话',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '令牌',
  `login_fail` tinyint(2) NULL DEFAULT NULL COMMENT '登录失败次数',
  `vcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '验证码',
  `role` tinyint(2) NULL DEFAULT NULL COMMENT '权限[0:系统管理员 1:普通管理员]',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '注册时间戳(创建该记录的时间戳)',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间戳',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', '系统管理员', '88928e83afcf10d4c21dae7c1facb170f02cd61b', 'IMD-j', '/static/source/img/admin.jpeg', 0, '2019-04-01', NULL, NULL, '36eee82fbbe4eef26632ab90e1bdb145', 0, NULL, 0, 1, 0, 1554082835, 1554082835);

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
  `admin_log_type` tinyint(2) NULL DEFAULT NULL COMMENT '日志类型[0:增 1:删 2:改 3:查 4:其他]',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_log
-- ----------------------------
INSERT INTO `admin_log` VALUES (1, 1, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554083423, 1554083423);
INSERT INTO `admin_log` VALUES (2, 1, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554083458, 1554083458);
INSERT INTO `admin_log` VALUES (3, 1, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554083500, 1554083500);
INSERT INTO `admin_log` VALUES (4, 1, 'http://www.firefly.test:81/login.html', 'http://www.firefly.test:81/admin/login/check.html', 'admin', '登录成功', '127.0.0.1', '中国--湖北省-武汉市-电信', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3724.8 Safari/537.36', 4, 1, 0, 1554090071, 1554090071);

-- ----------------------------
-- Table structure for email
-- ----------------------------
DROP TABLE IF EXISTS `email`;
CREATE TABLE `email`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户id',
  `admin_id` bigint(20) NULL DEFAULT NULL COMMENT '管理员id',
  `to` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '收件人',
  `from` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '发件人',
  `subject` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '主题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `email_type` tinyint(1) NULL DEFAULT NULL COMMENT '邮件类型[0:html 1:txt]',
  `is_success` tinyint(1) NULL DEFAULT NULL COMMENT '是否发送成功[0:失败 1:成功]',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for file
-- ----------------------------
DROP TABLE IF EXISTS `file`;
CREATE TABLE `file`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `folder_id` bigint(20) NULL DEFAULT NULL COMMENT '目录编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件名[old_name]',
  `save_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '保存后的文件名',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件类型(mime)',
  `ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件后缀',
  `size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件大小[Byte]',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '物理存储路径',
  `net_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '网络路径',
  `user_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户路径[关联folder表]',
  `old_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '上一次路径[关联folder表]',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件描述',
  `is_encrypt` tinyint(1) NULL DEFAULT NULL COMMENT '是否加密[0:没加密 1:加密]',
  `md5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件md5',
  `sha1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件sha1',
  `share_frequency` int(11) NULL DEFAULT NULL COMMENT '分享次数',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
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
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '路径[/开头]',
  `remark` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
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
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '规则',
  `menu_type` tinyint(1) NULL DEFAULT NULL COMMENT '菜单类型[0:不是菜单 1:链接 2:图片 3:文字 4:自定义]',
  `weigh` bigint(20) NULL DEFAULT NULL COMMENT '排序',
  `level` tinyint(2) NULL DEFAULT NULL COMMENT '级别',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, 0, NULL, '左侧导航栏', NULL, 1, 1, 0, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (2, 0, NULL, '顶部左侧导航', NULL, 1, 2, 0, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (3, 0, NULL, '顶部右侧导航', NULL, 1, 3, 0, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (4, 1, NULL, '控制台', NULL, 1, 4, 1, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (5, 4, NULL, '主页1', 'admin/index/index', 1, 5, 2, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (6, 4, NULL, '主页2', 'admin/index/index2', 1, 6, 2, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (7, 2, NULL, '顶部1', NULL, 1, 7, 1, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (8, 2, NULL, '顶部2', NULL, 1, 8, 1, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (9, 8, NULL, '顶部2 - 1', NULL, 1, 9, 2, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (10, 3, NULL, '顶部右1', NULL, 1, 10, 1, 1, 0, NULL, NULL);
INSERT INTO `menu` VALUES (11, 7, NULL, '顶部1 - 1', NULL, 1, 11, 2, 1, 0, NULL, NULL);

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
  `share_type` tinyint(1) NULL DEFAULT NULL COMMENT '分享类型[0:完全公开 1:公开有密码 2:限期分享 3:次数分享]',
  `share_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分享密码',
  `expire_time` int(10) NULL DEFAULT NULL COMMENT '到期时间',
  `frquency` int(11) NULL DEFAULT NULL COMMENT '使用次数',
  `use_frequency` int(11) NULL DEFAULT NULL COMMENT '已使用次数',
  `allow_comment` tinyint(1) NULL DEFAULT NULL COMMENT '是否允许评论[0:不允许 1:允许]',
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'ip地址',
  `location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '实际地理位置',
  `show_location` tinyint(1) NULL DEFAULT NULL COMMENT '是否显示地点[0: 不显示 1:显示]',
  `custom_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户自定义地理位置',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '代理信息',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
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
  `show_location` tinyint(1) NULL DEFAULT NULL COMMENT '是否显示地点[0: 不显示 1:显示]',
  `custom_location` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户自定义地理位置',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '代理信息',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
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
  `to_id` bigint(20) NULL DEFAULT NULL COMMENT '收件人id[对应user_id]',
  `from_id` bigint(20) NULL DEFAULT NULL COMMENT '发件人id[对应user_id和admin_id]',
  `subject` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '主题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '内容',
  `is_system` tinyint(1) NULL DEFAULT NULL COMMENT '是否系统邮件[0:否 1:是]',
  `is_read` tinyint(1) NULL DEFAULT NULL COMMENT '是否已读[0:未读 1:已读]',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
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
  `is_read` tinyint(1) NULL DEFAULT NULL COMMENT '是否已读[0:未读 1:已读]',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
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
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名称',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '值',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for up_down
-- ----------------------------
DROP TABLE IF EXISTS `up_down`;
CREATE TABLE `up_down`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户编号',
  `file_id` bigint(20) NULL DEFAULT NULL COMMENT '文件编号',
  `up_type` tinyint(1) NULL DEFAULT NULL COMMENT '类型[0:上传 1:下载]',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
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
  `gender` tinyint(1) NULL DEFAULT NULL COMMENT '性别[0:保密 1:女 2:.男]',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `tel` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '电话',
  `role` tinyint(2) NULL DEFAULT NULL COMMENT '权限',
  `qq` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'qq',
  `born` date NULL DEFAULT NULL COMMENT '出生年月',
  `sign` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '签名',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述',
  `score` int(10) NULL DEFAULT NULL COMMENT '积分',
  `level` tinyint(4) NULL DEFAULT NULL COMMENT '等级',
  `decorate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '装饰',
  `file_password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件密码',
  `total_size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '盘空间[单位Byte]',
  `use_size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '已使用盘空间[单位Byte]',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '令牌',
  `login_fail` tinyint(2) NULL DEFAULT NULL COMMENT '登录失败次数',
  `login_day` int(10) NULL DEFAULT NULL COMMENT '连续登录天数',
  `login_max_day` int(10) NULL DEFAULT NULL COMMENT '最多连续登录天数',
  `login_total_day` int(10) NULL DEFAULT NULL COMMENT '总登录天数',
  `vcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '验证码',
  `invite_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邀请码',
  `reg_invite_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '受邀码',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '注册时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最近一次更新时间',
  `signin_day` int(10) NULL DEFAULT NULL COMMENT '当前连续签到天数',
  `siginin_max_day` int(10) NULL DEFAULT NULL COMMENT '最多一次连续签到天数',
  `signin_total_day` int(10) NULL DEFAULT NULL COMMENT '总签到天数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户来源信息',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态[0:隐藏 1:显示]',
  `is_deleted` tinyint(1) NULL DEFAULT NULL COMMENT '是否删除[0:未删除 1:已删除]',
  `user_log_type` tinyint(2) NULL DEFAULT NULL COMMENT '日志类型[0:增 1:删 2:改 3:查 4:登录 等等]',
  `regtime` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `uptime` int(10) NULL DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
