/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 100134
Source Host           : localhost:3306
Source Database       : report_admin

Target Server Type    : MYSQL
Target Server Version : 100134
File Encoding         : 65001

Date: 2018-12-30 15:53:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `MID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Menuname` varchar(255) NOT NULL,
  `ParentID` int(11) DEFAULT NULL,
  `Mfun` varchar(255) DEFAULT NULL,
  `WebPage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MID`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '产量报表', '0', null, null);
INSERT INTO `menu` VALUES ('2', '总产量报表', '1', 'clordernumRp()', 'view/CLRp/cl.html');
INSERT INTO `menu` VALUES ('3', '业务类别报表', '1', 'clywlbRp()', 'view/CLRp/clywlb.html');
INSERT INTO `menu` VALUES ('4', '部门产量报表', '1', 'cldeptRp()', 'view/CLRp/cldept.html');
INSERT INTO `menu` VALUES ('5', '客户产量汇总表', '1', 'clcustomerRp()', 'view/CLRp/clcustomer.html');
INSERT INTO `menu` VALUES ('6', '订单量报表', '0', null, null);
INSERT INTO `menu` VALUES ('7', '订单数量报表（米数）', '6', 'clordernumRp()', 'view/DDLRP/clordernum.html');
INSERT INTO `menu` VALUES ('8', '订单数量报表（个数）', '6', 'ddnumRp()', 'view/DDLRP/ddnum.html');
INSERT INTO `menu` VALUES ('9', '订单数量报表（客户）', '6', 'ddcustomerRp()', 'view/DDLRP/ddcustomer.html');
INSERT INTO `menu` VALUES ('10', '订单数量报表（部门）', '6', 'dddeptRp()', 'view/DDLRP/dddept.html');
INSERT INTO `menu` VALUES ('11', '坯布报表', '0', null, null);
INSERT INTO `menu` VALUES ('12', '坯布入库报表', '11', 'pbrkRp()', 'view/PBRP/pbrk.html');
INSERT INTO `menu` VALUES ('13', '坯布入库报表（客户）', '11', 'pbrkcuRp()', 'view/PBRP/pbrkcu.html');
INSERT INTO `menu` VALUES ('14', '坯布出库报表', '11', 'pbckRp()', 'view/PBRP/pbck.html');
INSERT INTO `menu` VALUES ('15', '坯布出库报表（客户）', '11', 'pbckcuRp()', 'view/PBRP/pbckcu.html');
INSERT INTO `menu` VALUES ('16', '成品报表', '0', null, null);
INSERT INTO `menu` VALUES ('17', '成品入库报表', '16', 'cprkRp()', 'view/CPRP/cprk.html');
INSERT INTO `menu` VALUES ('18', '成品入库报表（客户）', '16', 'cprkcuRp()', 'view/CPRP/cprkcu.html');
INSERT INTO `menu` VALUES ('19', '成品出库报表', '16', 'cpckRp()', 'view/CPRP/cpck.html');
INSERT INTO `menu` VALUES ('20', '成品出库报表（客户）', '16', 'cpckcuRp()', 'view/CPRP/cpckcu.html');
INSERT INTO `menu` VALUES ('21', '后台管理', '-1', 'admin()', 'view/User/admin.html');
INSERT INTO `menu` VALUES ('22', '投屏显示', '-2', 'showRp()', 'view/Screen/loopReport.html');
INSERT INTO `menu` VALUES ('23', '业务员数据分析', '0', null, null);
INSERT INTO `menu` VALUES ('24', '业务员大货分析', '23', 'ywydhRp()', 'view/YWYRp/ywydh.html');
INSERT INTO `menu` VALUES ('25', '业务员放样分析', '23', 'ywyybRp()', 'view/YWYRp/ywyyb.html');
INSERT INTO `menu` VALUES ('26', '业务员规格分析', '23', 'ywyggRp()', 'view/YWYRp/ywygg.html');
INSERT INTO `menu` VALUES ('27', '客户数据分析', '0', null, null);
INSERT INTO `menu` VALUES ('28', '客户大货分析', '27', 'khdhybRp()', 'view/KHRp/khdhyb.html');
INSERT INTO `menu` VALUES ('29', '客户规格分析', '27', 'khggRp()', 'view/KHRp/khgg.html');
INSERT INTO `menu` VALUES ('30', '业务员价格分析', '23', 'ywyjgRp()', 'view/YWYRp/ywyjg.html');
INSERT INTO `menu` VALUES ('31', '部门价格分析', '23', 'bmjgRp()', 'view/YWYRp/bmjg.html');
INSERT INTO `menu` VALUES ('32', '规格分析', '27', 'specRp()', 'view/KHRp/spec.html');
INSERT INTO `menu` VALUES ('33', '机台报表', '0', null, null);
INSERT INTO `menu` VALUES ('34', '机台产量报表', '33', 'jtclRp()', 'view/JTRp/jtcl.html');

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `PID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MenuID` int(10) NOT NULL,
  PRIMARY KEY (`PID`,`MenuID`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of permission
-- ----------------------------
INSERT INTO `permission` VALUES ('1', '1');
INSERT INTO `permission` VALUES ('2', '2');
INSERT INTO `permission` VALUES ('3', '3');
INSERT INTO `permission` VALUES ('4', '4');
INSERT INTO `permission` VALUES ('5', '5');
INSERT INTO `permission` VALUES ('6', '6');
INSERT INTO `permission` VALUES ('7', '7');
INSERT INTO `permission` VALUES ('8', '8');
INSERT INTO `permission` VALUES ('9', '9');
INSERT INTO `permission` VALUES ('10', '10');
INSERT INTO `permission` VALUES ('11', '11');
INSERT INTO `permission` VALUES ('12', '12');
INSERT INTO `permission` VALUES ('13', '13');
INSERT INTO `permission` VALUES ('14', '14');
INSERT INTO `permission` VALUES ('15', '15');
INSERT INTO `permission` VALUES ('16', '16');
INSERT INTO `permission` VALUES ('17', '17');
INSERT INTO `permission` VALUES ('18', '18');
INSERT INTO `permission` VALUES ('19', '19');
INSERT INTO `permission` VALUES ('20', '20');
INSERT INTO `permission` VALUES ('21', '21');
INSERT INTO `permission` VALUES ('22', '22');
INSERT INTO `permission` VALUES ('23', '23');
INSERT INTO `permission` VALUES ('24', '24');
INSERT INTO `permission` VALUES ('25', '25');
INSERT INTO `permission` VALUES ('26', '26');
INSERT INTO `permission` VALUES ('27', '27');
INSERT INTO `permission` VALUES ('28', '28');
INSERT INTO `permission` VALUES ('29', '29');
INSERT INTO `permission` VALUES ('30', '30');
INSERT INTO `permission` VALUES ('31', '31');
INSERT INTO `permission` VALUES ('32', '32');
INSERT INTO `permission` VALUES ('33', '33');
INSERT INTO `permission` VALUES ('34', '34');

-- ----------------------------
-- Table structure for rolepermissions
-- ----------------------------
DROP TABLE IF EXISTS `rolepermissions`;
CREATE TABLE `rolepermissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `RID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of rolepermissions
-- ----------------------------
INSERT INTO `rolepermissions` VALUES ('1', '1', '1');
INSERT INTO `rolepermissions` VALUES ('3', '1', '3');
INSERT INTO `rolepermissions` VALUES ('4', '1', '4');
INSERT INTO `rolepermissions` VALUES ('5', '1', '5');
INSERT INTO `rolepermissions` VALUES ('6', '1', '6');
INSERT INTO `rolepermissions` VALUES ('7', '1', '7');
INSERT INTO `rolepermissions` VALUES ('8', '1', '8');
INSERT INTO `rolepermissions` VALUES ('9', '1', '9');
INSERT INTO `rolepermissions` VALUES ('10', '1', '10');
INSERT INTO `rolepermissions` VALUES ('11', '1', '11');
INSERT INTO `rolepermissions` VALUES ('12', '1', '12');
INSERT INTO `rolepermissions` VALUES ('13', '1', '13');
INSERT INTO `rolepermissions` VALUES ('14', '1', '14');
INSERT INTO `rolepermissions` VALUES ('15', '1', '15');
INSERT INTO `rolepermissions` VALUES ('16', '1', '16');
INSERT INTO `rolepermissions` VALUES ('17', '1', '17');
INSERT INTO `rolepermissions` VALUES ('18', '1', '18');
INSERT INTO `rolepermissions` VALUES ('19', '1', '19');
INSERT INTO `rolepermissions` VALUES ('20', '1', '20');
INSERT INTO `rolepermissions` VALUES ('22', '2', '1');
INSERT INTO `rolepermissions` VALUES ('24', '2', '3');
INSERT INTO `rolepermissions` VALUES ('27', '2', '6');
INSERT INTO `rolepermissions` VALUES ('28', '2', '7');
INSERT INTO `rolepermissions` VALUES ('29', '2', '8');
INSERT INTO `rolepermissions` VALUES ('30', '2', '9');
INSERT INTO `rolepermissions` VALUES ('31', '2', '10');
INSERT INTO `rolepermissions` VALUES ('82', '1', '21');
INSERT INTO `rolepermissions` VALUES ('89', '2', '4');
INSERT INTO `rolepermissions` VALUES ('90', '2', '5');
INSERT INTO `rolepermissions` VALUES ('97', '2', '11');
INSERT INTO `rolepermissions` VALUES ('98', '2', '12');
INSERT INTO `rolepermissions` VALUES ('99', '2', '13');
INSERT INTO `rolepermissions` VALUES ('100', '2', '14');
INSERT INTO `rolepermissions` VALUES ('101', '2', '15');
INSERT INTO `rolepermissions` VALUES ('102', '2', '16');
INSERT INTO `rolepermissions` VALUES ('103', '2', '17');
INSERT INTO `rolepermissions` VALUES ('104', '2', '18');
INSERT INTO `rolepermissions` VALUES ('105', '2', '19');
INSERT INTO `rolepermissions` VALUES ('106', '2', '20');
INSERT INTO `rolepermissions` VALUES ('107', '1', '22');
INSERT INTO `rolepermissions` VALUES ('108', '5', '22');
INSERT INTO `rolepermissions` VALUES ('121', '1', '23');
INSERT INTO `rolepermissions` VALUES ('123', '1', '24');
INSERT INTO `rolepermissions` VALUES ('124', '1', '25');
INSERT INTO `rolepermissions` VALUES ('125', '1', '26');
INSERT INTO `rolepermissions` VALUES ('126', '1', '27');
INSERT INTO `rolepermissions` VALUES ('127', '1', '28');
INSERT INTO `rolepermissions` VALUES ('129', '1', '29');
INSERT INTO `rolepermissions` VALUES ('130', '2', '23');
INSERT INTO `rolepermissions` VALUES ('131', '2', '24');
INSERT INTO `rolepermissions` VALUES ('132', '2', '25');
INSERT INTO `rolepermissions` VALUES ('133', '2', '26');
INSERT INTO `rolepermissions` VALUES ('134', '2', '27');
INSERT INTO `rolepermissions` VALUES ('135', '2', '28');
INSERT INTO `rolepermissions` VALUES ('136', '2', '29');
INSERT INTO `rolepermissions` VALUES ('137', '1', '30');
INSERT INTO `rolepermissions` VALUES ('138', '1', '31');
INSERT INTO `rolepermissions` VALUES ('139', '1', '32');
INSERT INTO `rolepermissions` VALUES ('140', '2', '30');
INSERT INTO `rolepermissions` VALUES ('141', '2', '31');
INSERT INTO `rolepermissions` VALUES ('142', '2', '32');
INSERT INTO `rolepermissions` VALUES ('143', '1', '33');
INSERT INTO `rolepermissions` VALUES ('144', '1', '34');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `RID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) NOT NULL,
  PRIMARY KEY (`RID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', '超级管理员');
INSERT INTO `roles` VALUES ('2', '普通用户');
INSERT INTO `roles` VALUES ('5', '投屏用户');

-- ----------------------------
-- Table structure for screenparam
-- ----------------------------
DROP TABLE IF EXISTS `screenparam`;
CREATE TABLE `screenparam` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datetype` int(10) DEFAULT NULL,
  `lx` varchar(255) DEFAULT NULL,
  `cycle` int(11) DEFAULT NULL,
  `dtype` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of screenparam
-- ----------------------------
INSERT INTO `screenparam` VALUES ('1', '1', 'cl', '15', '日');
INSERT INTO `screenparam` VALUES ('2', '1', 'cprk', '15', '日');
INSERT INTO `screenparam` VALUES ('3', '1', 'cpck', '7', '日');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uAccount` varchar(255) NOT NULL,
  `uPassWord` varchar(255) NOT NULL,
  `uNickName` varchar(255) NOT NULL,
  PRIMARY KEY (`uID`,`uAccount`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'admini', 'SlSZboc8ZVMxC7gsIzYUMx+ju/juDEZgwE3vfQ8gZ1M=', '系统管理员');

-- ----------------------------
-- Table structure for userroles
-- ----------------------------
DROP TABLE IF EXISTS `userroles`;
CREATE TABLE `userroles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `UID` int(11) NOT NULL,
  `RID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userroles
-- ----------------------------
INSERT INTO `userroles` VALUES ('0', '1', '1');
DROP TRIGGER IF EXISTS `trigger_menu_permission`;
DELIMITER ;;
CREATE TRIGGER `trigger_menu_permission` AFTER INSERT ON `menu` FOR EACH ROW begin
insert into permission(MenuID) values(new.MID);
end
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_delete_menu`;
DELIMITER ;;
CREATE TRIGGER `trigger_delete_menu` AFTER DELETE ON `menu` FOR EACH ROW begin 
	delete from permission where MenuID = old.MID;
end
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_permission_admin`;
DELIMITER ;;
CREATE TRIGGER `trigger_permission_admin` AFTER INSERT ON `permission` FOR EACH ROW begin 
	insert into rolepermissions(RID,PID) values(1,new.PID);
end
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_delete_permission`;
DELIMITER ;;
CREATE TRIGGER `trigger_delete_permission` AFTER DELETE ON `permission` FOR EACH ROW begin 
	delete from rolepermissions where PID = old.PID;
end
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_delete_rolepermissions`;
DELIMITER ;;
CREATE TRIGGER `trigger_delete_rolepermissions` AFTER DELETE ON `roles` FOR EACH ROW begin 
	delete from rolepermissions where RID = old.RID;
end
;;
DELIMITER ;
DROP TRIGGER IF EXISTS `trigger_delete_userroles`;
DELIMITER ;;
CREATE TRIGGER `trigger_delete_userroles` AFTER DELETE ON `user` FOR EACH ROW begin 
	delete from userroles where UID = old.UID;
end
;;
DELIMITER ;
