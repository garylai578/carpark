/*
Navicat MySQL Data Transfer

Source Server         : rockoa
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-06-19 16:10:17
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for carpass
-- ----------------------------
DROP TABLE IF EXISTS `carpass`;
CREATE TABLE `carpass` (
  `id` int(32) NOT NULL AUTO_INCREMENT COMMENT 'car id',
  `cid` int(32) DEFAULT NULL,
  `directType` tinyint(4) DEFAULT NULL COMMENT '1-入口，2-出口',
  `passTime` datetime DEFAULT NULL COMMENT '通过时间',
  `sendTime` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '上传的时间',
  `picPath1` varchar(255) DEFAULT NULL COMMENT '抓拍的图片地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
