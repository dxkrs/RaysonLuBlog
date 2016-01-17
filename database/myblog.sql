/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : myblog

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2016-01-17 13:49:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `tag_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cover_pic_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci,
  `is_showed` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of articles
-- ----------------------------
INSERT INTO `articles` VALUES ('1', '1', '', null, 'test', '<p><em style=\"white-space: normal;\">te</em>st测<strong style=\"white-space: normal;\">试</strong></p>', '', '1', '2015-11-20 17:37:11', '2015-11-20 17:37:11', null);
INSERT INTO `articles` VALUES ('2', '6', '1111', null, 'test2', '<p>test222222222<br/></p><p>22</p><p>2</p><p>2</p><p>2</p><p>2</p><p>2</p><p>2</p><p>2</p><p>2</p><p>2</p><p>2</p><p>2</p>', 'testdesc', '1', '2015-11-20 17:38:25', '2015-11-20 17:38:25', null);
INSERT INTO `articles` VALUES ('3', '6', 'ddddd', null, 'testsces', '<p>adfaawefasdfasdf</p><p>asfasd</p><p>asdfasd</p><p>asdf</p><p>asdf</p><p>asdf</p><p>asdf</p><p>asdfa</p><p>df</p>', 'asdfadsfadfasf', '1', '2015-11-20 18:19:32', '2015-11-20 18:19:32', null);
INSERT INTO `articles` VALUES ('5', '6', 'ddddd', null, 'testsces', '<p>adfaawefasdfasdf</p><p>asfasd</p><p>asdfasd</p><p>asdf</p><p>asdf</p><p>asdf</p><p>asdf</p><p>asdfa</p><p>df</p>', 'asdfadsfadfasf', '1', '2015-11-20 18:20:26', '2015-11-20 18:20:26', null);

-- ----------------------------
-- Table structure for article_view
-- ----------------------------
DROP TABLE IF EXISTS `article_view`;
CREATE TABLE `article_view` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `art_id` int(11) NOT NULL,
  `view_number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of article_view
-- ----------------------------

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `as_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `seo_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `seo_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_cate_name_unique` (`category_name`),
  UNIQUE KEY `category_as_name_unique` (`as_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES ('1', 'test1', 'test1', '0', 'test1', 'test1', 'test1', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `categories` VALUES ('2', 'test2', 'test2', '0', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `categories` VALUES ('3', 'test3', 'test3', '0', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `categories` VALUES ('4', 'test11', 'test11', '1', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `categories` VALUES ('5', 'test12', 'test12', '1', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `categories` VALUES ('6', 'test111', 'test111', '4', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `categories` VALUES ('7', 'test4', 'test4', '0', 'a', 'b', 'c', '2015-11-15 16:12:01', '2015-11-15 16:12:01');
INSERT INTO `categories` VALUES ('8', 'test5', 'test5', '0', 'a', 'b', 'c', '2015-11-15 16:13:13', '2015-11-15 16:13:13');
INSERT INTO `categories` VALUES ('9', 'test6', 'test6', '0', 'a', 'b', 'c', '2015-11-15 16:14:00', '2015-11-15 16:14:00');

-- ----------------------------
-- Table structure for tags
-- ----------------------------
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `use_number` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tags
-- ----------------------------
INSERT INTO `tags` VALUES ('1', 'testtag', '0', '2015-11-17 17:38:48', '2015-11-17 17:38:48');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `desc` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
