# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.1.63-log)
# Database: proculus
# Generation Time: 2017-11-23 00:11:49 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table t_admin
# ------------------------------------------------------------

CREATE TABLE `t_admin` (
  `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` int(11) NOT NULL COMMENT '密码加盐',
  `company` int(10) NOT NULL COMMENT '所属公司',
  `user_status` int(11) NOT NULL COMMENT '登录状态0正常1冻结',
  `create_time` bigint(20) NOT NULL COMMENT '创建时间',
  `update_time` bigint(20) NOT NULL COMMENT '上次修改信息时间',
  `login_time` bigint(20) NOT NULL COMMENT '上次登录时间',
  `create_id` int(11) NOT NULL COMMENT '创建人id',
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table t_category
# ------------------------------------------------------------

CREATE TABLE `t_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `level` tinyint(11) NOT NULL COMMENT '菜单级别',
  `parent` int(11) NOT NULL COMMENT '上级菜单',
  `create_time` bigint(20) NOT NULL COMMENT '创建时间',
  `operater` varchar(128) NOT NULL DEFAULT '' COMMENT '创建人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table t_keywords
# ------------------------------------------------------------

CREATE TABLE `t_keywords` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(64) NOT NULL DEFAULT '' COMMENT '关键字',
  `use_count` int(11) NOT NULL COMMENT '使用次数',
  `create_time` bigint(20) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table t_news
# ------------------------------------------------------------

CREATE TABLE `t_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL COMMENT '标题',
  `pic` text NOT NULL COMMENT '文章图片',
  `content` text NOT NULL COMMENT '文章内容',
  `keywords` varchar(512) NOT NULL DEFAULT '' COMMENT '文章关键字',
  `status` tinyint(4) NOT NULL COMMENT '新闻状态',
  `create_time` bigint(20) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table t_product
# ------------------------------------------------------------

CREATE TABLE `t_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL COMMENT '产品名称',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `create_time` bigint(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table t_product_type
# ------------------------------------------------------------

CREATE TABLE `t_product_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL COMMENT '产品id',
  `cate_id` int(11) NOT NULL COMMENT '属性id',
  `cate_val` varchar(128) NOT NULL DEFAULT '' COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table t_upload
# ------------------------------------------------------------

CREATE TABLE `t_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(256) NOT NULL DEFAULT '' COMMENT '上传文件名',
  `filetype` varchar(20) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` decimal(20,2) NOT NULL COMMENT '文件大小',
  `create_time` bigint(20) NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
