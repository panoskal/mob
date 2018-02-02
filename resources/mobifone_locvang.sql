/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : mobifone_locvang

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2018-02-02 17:43:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cms_configuration
-- ----------------------------
DROP TABLE IF EXISTS `cms_configuration`;
CREATE TABLE `cms_configuration` (
  `id` int(255) NOT NULL,
  `keyname` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cms_configuration
-- ----------------------------
INSERT INTO `cms_configuration` VALUES ('1', 'TIMEZONE', 'Asia/Ho_Chi_Minh', '');
INSERT INTO `cms_configuration` VALUES ('2', 'PREFIX', '84', 'Country code');
INSERT INTO `cms_configuration` VALUES ('3', 'DEFAULT_LANG', 'vn', '');
INSERT INTO `cms_configuration` VALUES ('4', 'ALL_LANG', 'vn', '');
INSERT INTO `cms_configuration` VALUES ('5', 'LOGINURL', 'http://mobifone-ws.upstreamsystems.com:8080/mobifone-vietnam-ws/WebSupportService.wsdl', 'URL for validating user');
INSERT INTO `cms_configuration` VALUES ('6', 'CAMPAIGNID', 'mobifone', '');
INSERT INTO `cms_configuration` VALUES ('7', 'USERIDURL', 'http://mobifone-ws.upstreamsystems.com:8080/mobifone-vietnam-ws/WebSupportService.wsdl', 'No trailing slashes');
INSERT INTO `cms_configuration` VALUES ('8', 'DRAW_URL', 'http://mobifone-ws.upstreamsystems.com:8080/mobifone-vietnam-ws/WebSupportService.wsdl', 'URL to check winners (no trailing slashes)');
INSERT INTO `cms_configuration` VALUES ('9', 'DRAW_USER', 'qa', '');
INSERT INTO `cms_configuration` VALUES ('10', 'DRAW_PASS', 'qa', '');
INSERT INTO `cms_configuration` VALUES ('11', 'RECAPTCHA_KEY', '6Lelx0MUAAAAAFDB2XLUJVxZyUNbmWfnoon5qcv-', '');
INSERT INTO `cms_configuration` VALUES ('12', 'GANALYTICS', 'UA-44777683-1', 'Google key for analytics');
INSERT INTO `cms_configuration` VALUES ('13', 'DEBUGGING', 'false', 'Set debugging mode on/off by setting this value true or false');
INSERT INTO `cms_configuration` VALUES ('14', 'DEBUGPLAINCURL', 'false', 'Set debugging mode on/off by setting this value true or false');
INSERT INTO `cms_configuration` VALUES ('15', 'CACHE', 'http://locvang.mobifone.vn', 'URL: the of the cache server (no trailing slashes)');
INSERT INTO `cms_configuration` VALUES ('16', 'DOMAIN', 'http://locvang.mobifone.vn', 'URL: the of the site (no trailing slashes)');
INSERT INTO `cms_configuration` VALUES ('17', 'VERSIONING', '100', 'NUMBER: version number Always greater number than the previews one');
INSERT INTO `cms_configuration` VALUES ('18', 'CONFIGURATION_TITLE', 'mobifone', 'Enter the general site title');
INSERT INTO `cms_configuration` VALUES ('19', 'GENERAL_DESCRIPTION', 'meta', 'Enter general site meta description');
INSERT INTO `cms_configuration` VALUES ('20', 'GENERAL_KEYWORDS', 'meta', 'Enter general site meta keywords');
INSERT INTO `cms_configuration` VALUES ('21', 'ONEPAGE', 'false', 'TRUE|FALSE: toggle between one page(with scroll down) and multiple pages');
INSERT INTO `cms_configuration` VALUES ('22', 'CHECK_LOGS_URL', 'http://localhost/logs', 'Url for checking error logs');

-- ----------------------------
-- Table structure for cms_menu
-- ----------------------------
DROP TABLE IF EXISTS `cms_menu`;
CREATE TABLE `cms_menu` (
  `slug` varchar(255) DEFAULT NULL,
  `order` int(255) DEFAULT NULL,
  `has_parent` varchar(255) NOT NULL,
  `is_not_link` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cms_menu
-- ----------------------------
INSERT INTO `cms_menu` VALUES ('Home', '1', '0', '0');
INSERT INTO `cms_menu` VALUES ('participation', '4', '0', '0');
INSERT INTO `cms_menu` VALUES ('main parent', '2', '0', '1');
INSERT INTO `cms_menu` VALUES ('submenu', '2', 'other parent', '0');
INSERT INTO `cms_menu` VALUES ('submenu2', '1', 'main parent', '0');
INSERT INTO `cms_menu` VALUES ('other parent', '3', '0', '1');
INSERT INTO `cms_menu` VALUES ('sbd', '1', 'other parent', '0');

-- ----------------------------
-- Table structure for cms_pages
-- ----------------------------
DROP TABLE IF EXISTS `cms_pages`;
CREATE TABLE `cms_pages` (
  `id` int(11) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `lang` varchar(25) DEFAULT NULL,
  `enabled` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cms_pages
-- ----------------------------
INSERT INTO `cms_pages` VALUES ('1', 'home', 'home', '<p>home<br></p>', '', '', 'vn', '1');
INSERT INTO `cms_pages` VALUES ('2', 'participation', 'participation', '<p>participation<br></p>', '', '', 'vn', '1');
INSERT INTO `cms_pages` VALUES ('3', 'submenu', 'submenu', '<p>participation<br></p>', '', '', 'vn', '1');
INSERT INTO `cms_pages` VALUES ('4', 'submenu2', 'submenu2', '<p>participation<br></p>', '', '', 'vn', '1');
INSERT INTO `cms_pages` VALUES ('5', 'sbd', 'sbd', 'sbd', '', null, 'vn', '1');

-- ----------------------------
-- Table structure for cms_users
-- ----------------------------
DROP TABLE IF EXISTS `cms_users`;
CREATE TABLE `cms_users` (
  `user` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cms_users
-- ----------------------------
INSERT INTO `cms_users` VALUES ('user', '$2y$12$dUWjAN0AsN/qmbn5yJCsvOv5de2h7JSvXHnSI6EeocqOUr5Jy/j66');

-- ----------------------------
-- Table structure for cms_winners
-- ----------------------------
DROP TABLE IF EXISTS `cms_winners`;
CREATE TABLE `cms_winners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `winnerdate` date DEFAULT NULL,
  `prize` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cms_winners
-- ----------------------------
