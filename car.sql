/*
Navicat MySQL Data Transfer

Source Server         : rockoa
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-06-19 16:10:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for car
-- ----------------------------
DROP TABLE IF EXISTS `car`;
CREATE TABLE `car` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `license` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '车牌号',
  `licenseColor` int(32) DEFAULT NULL COMMENT '车牌颜色',
  `type` varchar(255) CHARACTER SET utf8 DEFAULT 'K33' COMMENT '车辆类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
