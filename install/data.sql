-- MySQL dump 10.13  Distrib 5.5.53, for Win32 (AMD64)
--
-- Host: localhost    Database: cms
-- ------------------------------------------------------
-- Server version	5.5.53

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ad`
--

DROP TABLE IF EXISTS `ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ad` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `kinds` int(2) NOT NULL,
  `title` varchar(40) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `sort` int(2) NOT NULL,
  `status` int(2) unsigned NOT NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(2) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `titlecolor` varchar(255) NOT NULL,
  `fontstyle` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `content` mediumtext NOT NULL,
  `status` int(2) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `hits` int(11) unsigned NOT NULL,
  `posid` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目',
  `userid` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` varchar(40) NOT NULL DEFAULT '' COMMENT '发布用户名',
  `title` varchar(120) NOT NULL DEFAULT '' COMMENT '标题',
  `title_style` varchar(225) NOT NULL DEFAULT '' COMMENT '标题样式',
  `thumb` varchar(225) NOT NULL DEFAULT '' COMMENT '缩略图',
  `keywords` varchar(120) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` mediumtext NOT NULL,
  `content` text NOT NULL,
  `template` varchar(40) NOT NULL DEFAULT '',
  `posid` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0：待发布;1:发布',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许评论',
  `readgroup` varchar(100) NOT NULL DEFAULT '',
  `readpoint` smallint(5) NOT NULL DEFAULT '0' COMMENT '阅读收费',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `copyfrom` varchar(255) NOT NULL DEFAULT '' COMMENT '来源',
  `fromlink` varchar(255) NOT NULL DEFAULT '' COMMENT '来源网址',
  PRIMARY KEY (`id`),
  KEY `status` (`id`,`status`,`listorder`),
  KEY `catid` (`id`,`catid`,`status`),
  KEY `listorder` (`id`,`catid`,`status`,`listorder`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `attachment`
--

DROP TABLE IF EXISTS `attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` char(15) NOT NULL DEFAULT '' COMMENT '所属模块',
  `filename` char(50) NOT NULL DEFAULT '' COMMENT '文件名',
  `filepath` char(200) NOT NULL DEFAULT '' COMMENT '文件路径+文件名',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `fileext` char(10) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `uploadip` char(15) NOT NULL DEFAULT '' COMMENT '上传IP',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未审核1已审核-1不通过',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `manage_id` int(11) NOT NULL COMMENT '审核者id',
  `audit_time` int(11) NOT NULL COMMENT '审核时间',
  `use` varchar(200) DEFAULT NULL COMMENT '用处',
  `download` int(11) NOT NULL DEFAULT '0' COMMENT '下载量',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `filename` (`filename`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE,
  KEY `manage_id` (`manage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='附件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(255) NOT NULL DEFAULT '',
  `catdir` varchar(30) NOT NULL DEFAULT '',
  `parentdir` varchar(50) NOT NULL DEFAULT '',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `moduleid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `module` char(24) NOT NULL DEFAULT '',
  `arrparentid` varchar(255) NOT NULL DEFAULT '',
  `arrchildid` varchar(100) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `keywords` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ishtml` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `image` varchar(100) NOT NULL DEFAULT '',
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` varchar(100) NOT NULL DEFAULT '',
  `template_list` varchar(20) NOT NULL DEFAULT '',
  `template_show` varchar(20) NOT NULL DEFAULT '',
  `pagesize` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `readgroup` varchar(100) NOT NULL DEFAULT '',
  `listtype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`),
  KEY `listorder` (`listorder`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clt_article`
--

DROP TABLE IF EXISTS `clt_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clt_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(120) NOT NULL DEFAULT '',
  `title_style` varchar(225) NOT NULL DEFAULT '',
  `thumb` varchar(225) NOT NULL DEFAULT '',
  `keywords` varchar(120) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `content` text NOT NULL,
  `template` varchar(40) NOT NULL DEFAULT '',
  `posid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `readgroup` varchar(100) NOT NULL DEFAULT '',
  `readpoint` smallint(5) NOT NULL DEFAULT '0',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `copyfrom` varchar(255) NOT NULL DEFAULT 'CLTPHP',
  `fromlink` varchar(255) NOT NULL DEFAULT 'http://www.cltphp.com/',
  `address` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `status` (`id`,`status`,`listorder`),
  KEY `catid` (`id`,`catid`,`status`),
  KEY `listorder` (`id`,`catid`,`status`,`listorder`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `siteid` int(11) DEFAULT NULL COMMENT '站点ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `group` (`group`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `field`
--

DROP TABLE IF EXISTS `field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `field` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL DEFAULT '' COMMENT '字段名',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段k中文名称',
  `tips` varchar(150) NOT NULL DEFAULT '' COMMENT '默认输入提示',
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:不必填;1必填',
  `minlength` int(10) unsigned NOT NULL DEFAULT '0',
  `maxlength` int(10) unsigned NOT NULL DEFAULT '0',
  `pattern` varchar(255) NOT NULL DEFAULT '' COMMENT '验证类型',
  `errormsg` varchar(255) NOT NULL DEFAULT '' COMMENT '验证错误提示',
  `class` varchar(20) NOT NULL DEFAULT '' COMMENT 'id标识',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '字段类型',
  `setup` mediumtext NOT NULL,
  `ispost` tinyint(1) NOT NULL DEFAULT '0',
  `unpostgroup` varchar(60) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:正常;1:不可写;2:隐藏',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=226 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manage`
--

DROP TABLE IF EXISTS `manage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(20) DEFAULT NULL COMMENT '昵称',
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `thumb` int(11) NOT NULL DEFAULT '1' COMMENT '管理员头像',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `login_ip` varchar(100) DEFAULT NULL COMMENT '最后登录ip',
  `manage_cate_id` int(2) NOT NULL DEFAULT '1' COMMENT '管理员分组',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `nickname` (`nickname`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE,
  KEY `manage_cate_id` (`manage_cate_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manage_cate`
--

DROP TABLE IF EXISTS `manage_cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manage_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `permissions` text COMMENT '权限菜单',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `desc` text COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `name` (`name`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manage_log`
--

DROP TABLE IF EXISTS `manage_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manage_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manage_menu_id` int(11) NOT NULL COMMENT '操作菜单id',
  `manage_id` int(11) NOT NULL COMMENT '操作者id',
  `ip` varchar(100) DEFAULT NULL COMMENT '操作ip',
  `operation_id` varchar(200) DEFAULT NULL COMMENT '操作关联id',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `create_time` (`create_time`) USING BTREE,
  KEY `manage_id` (`manage_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=233 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `manage_menu`
--

DROP TABLE IF EXISTS `manage_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `manage_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL COMMENT '模块',
  `controller` varchar(100) NOT NULL COMMENT '控制器',
  `function` varchar(100) NOT NULL COMMENT '方法',
  `parameter` varchar(50) DEFAULT NULL COMMENT '参数',
  `description` varchar(250) DEFAULT NULL COMMENT '描述',
  `is_display` int(1) NOT NULL DEFAULT '1' COMMENT '1显示在左侧菜单2只作为节点',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1权限节点2普通节点',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级菜单0为顶级菜单',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `icon` varchar(100) DEFAULT NULL COMMENT '图标',
  `is_open` int(1) NOT NULL DEFAULT '0' COMMENT '0默认闭合1默认展开',
  `orders` int(11) NOT NULL DEFAULT '0' COMMENT '排序值，越小越靠前',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `module` (`module`) USING BTREE,
  KEY `controller` (`controller`) USING BTREE,
  KEY `function` (`function`) USING BTREE,
  KEY `is_display` (`is_display`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listfields` varchar(255) NOT NULL DEFAULT '',
  `setup` text NOT NULL,
  `listorder` smallint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `navigation`
--

DROP TABLE IF EXISTS `navigation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `navigation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `enname` varchar(255) DEFAULT NULL COMMENT '英文名称',
  `pid` int(11) DEFAULT NULL COMMENT '父级ID',
  `url` varchar(255) DEFAULT NULL COMMENT '链接地址',
  `model` varchar(255) DEFAULT NULL COMMENT '1.文章列表;2.图文列表;3产品列表;4.单页面',
  `window` int(2) DEFAULT NULL COMMENT '是否在新窗口打开：_self、_blank',
  `sort` int(4) DEFAULT NULL COMMENT '排序:数字越小越靠前',
  `status` int(2) DEFAULT NULL COMMENT '是否显示：1.显示2.不显示',
  `addtime` int(12) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(2) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `entitle` varchar(255) NOT NULL,
  `fontstyle` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `content` mediumtext NOT NULL,
  `status` int(2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `catid` int(2) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_auth`
--

DROP TABLE IF EXISTS `wx_auth`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_auth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `instance_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `authorizer_appid` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺的appid  授权之后不用刷新',
  `authorizer_refresh_token` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺授权之后的刷新token，每月刷新',
  `authorizer_access_token` varchar(255) NOT NULL DEFAULT '' COMMENT '店铺的公众号token，只有2小时',
  `func_info` varchar(1000) NOT NULL DEFAULT '' COMMENT '授权项目',
  `nick_name` varchar(50) NOT NULL DEFAULT '' COMMENT '公众号昵称',
  `head_img` varchar(255) NOT NULL DEFAULT '' COMMENT '公众号头像url',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '公众号原始账号',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT '公众号原始名称',
  `qrcode_url` varchar(255) NOT NULL DEFAULT '' COMMENT '公众号二维码url',
  `auth_time` int(11) DEFAULT '0' COMMENT '授权时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 COMMENT='店铺(实例)微信公众账号授权';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_config`
--

DROP TABLE IF EXISTS `wx_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `instance_id` int(11) NOT NULL DEFAULT '1' COMMENT '实例ID',
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT '配置项WCHAT,QQ,WPAY,ALIPAY...',
  `value` varchar(1000) NOT NULL DEFAULT '' COMMENT '配置值json',
  `desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '描述',
  `is_use` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否启用 1启用 0不启用',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=963 COMMENT='第三方配置表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_default_replay`
--

DROP TABLE IF EXISTS `wx_default_replay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_default_replay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `instance_id` int(11) NOT NULL COMMENT '店铺id',
  `reply_media_id` int(11) NOT NULL COMMENT '回复媒体内容id',
  `sort` int(11) NOT NULL,
  `create_time` int(11) DEFAULT '0',
  `modify_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384 COMMENT='关注时回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_fans`
--

DROP TABLE IF EXISTS `wx_fans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_fans` (
  `fans_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '粉丝ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员编号ID',
  `source_uid` int(11) NOT NULL DEFAULT '0' COMMENT '推广人uid',
  `instance_id` int(11) NOT NULL COMMENT '店铺ID',
  `nickname` varchar(255) NOT NULL COMMENT '昵称',
  `nickname_decode` varchar(255) DEFAULT '',
  `headimgurl` varchar(500) NOT NULL DEFAULT '' COMMENT '头像',
  `sex` smallint(6) NOT NULL DEFAULT '1' COMMENT '性别',
  `language` varchar(20) NOT NULL DEFAULT '' COMMENT '用户语言',
  `country` varchar(60) NOT NULL DEFAULT '' COMMENT '国家',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '城市',
  `district` varchar(255) NOT NULL DEFAULT '' COMMENT '行政区/县',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户的标识，对当前公众号唯一     用户的唯一身份ID',
  `unionid` varchar(255) NOT NULL DEFAULT '' COMMENT '粉丝unionid',
  `groupid` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝所在组id',
  `is_subscribe` bigint(1) NOT NULL DEFAULT '1' COMMENT '是否订阅',
  `memo` varchar(255) NOT NULL COMMENT '备注',
  `subscribe_date` int(11) DEFAULT '0' COMMENT '订阅时间',
  `unsubscribe_date` int(11) DEFAULT '0' COMMENT '解订阅时间',
  `update_date` int(11) DEFAULT '0' COMMENT '粉丝信息最后更新时间',
  PRIMARY KEY (`fans_id`),
  KEY `IDX_sys_weixin_fans_openid` (`openid`),
  KEY `IDX_sys_weixin_fans_unionid` (`unionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=1638 COMMENT='微信公众号获取粉丝列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_follow_replay`
--

DROP TABLE IF EXISTS `wx_follow_replay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_follow_replay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `instance_id` int(11) NOT NULL COMMENT '店铺id',
  `reply_media_id` int(11) NOT NULL COMMENT '回复媒体内容id',
  `sort` int(11) NOT NULL,
  `create_time` int(11) DEFAULT '0',
  `modify_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384 COMMENT='关注时回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_key_replay`
--

DROP TABLE IF EXISTS `wx_key_replay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_key_replay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `instance_id` int(11) NOT NULL COMMENT '店铺id',
  `key` varchar(255) NOT NULL COMMENT '关键词',
  `match_type` tinyint(4) NOT NULL COMMENT '匹配类型1模糊匹配2全部匹配',
  `reply_media_id` int(11) NOT NULL COMMENT '回复媒体内容id',
  `sort` int(11) NOT NULL,
  `create_time` int(11) DEFAULT '0',
  `modify_time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384 COMMENT='关键词回复';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_media`
--

DROP TABLE IF EXISTS `wx_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_media` (
  `media_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '图文消息id',
  `title` varchar(100) DEFAULT NULL,
  `instance_id` int(11) NOT NULL DEFAULT '0' COMMENT '实例id店铺id',
  `type` varchar(255) NOT NULL DEFAULT '1' COMMENT '类型1文本(项表无内容) 2单图文 3多图文',
  `sort` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT '0' COMMENT '创建日期',
  `modify_time` int(11) DEFAULT '0' COMMENT '修改日期',
  PRIMARY KEY (`media_id`),
  UNIQUE KEY `id` (`media_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=1170;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_media_item`
--

DROP TABLE IF EXISTS `wx_media_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_media_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `media_id` int(11) NOT NULL COMMENT '图文消息id',
  `title` varchar(100) DEFAULT NULL,
  `author` varchar(50) NOT NULL COMMENT '作者',
  `cover` varchar(200) NOT NULL COMMENT '图文消息封面',
  `show_cover_pic` tinyint(4) NOT NULL DEFAULT '1' COMMENT '封面图片显示在正文中',
  `summary` text,
  `content` text NOT NULL COMMENT '正文',
  `content_source_url` varchar(200) NOT NULL DEFAULT '' COMMENT '图文消息的原文地址，即点击“阅读原文”后的URL',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '阅读次数',
  PRIMARY KEY (`id`),
  KEY `id` (`media_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=712;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_menu`
--

DROP TABLE IF EXISTS `wx_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `instance_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `menu_name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `ico` varchar(32) NOT NULL DEFAULT '' COMMENT '菜图标单',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父菜单',
  `menu_event_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1普通url 2 图文素材 3 功能',
  `media_id` int(11) NOT NULL DEFAULT '0' COMMENT '图文消息ID',
  `menu_event_url` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单url',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '触发数',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期',
  `modify_date` int(11) DEFAULT '0' COMMENT '修改日期',
  PRIMARY KEY (`menu_id`),
  KEY `IDX_biz_shop_menu_orders` (`sort`),
  KEY `IDX_biz_shop_menu_shopId` (`instance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=1638 COMMENT='微设置->微店菜单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_user`
--

DROP TABLE IF EXISTS `wx_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表id',
  `uid` int(11) NOT NULL COMMENT 'uid',
  `wxname` varchar(60) NOT NULL COMMENT '公众号名称',
  `aeskey` varchar(256) NOT NULL DEFAULT '' COMMENT 'aeskey',
  `encode` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'encode',
  `appid` varchar(50) NOT NULL DEFAULT '' COMMENT 'appid',
  `appsecret` varchar(50) NOT NULL DEFAULT '' COMMENT 'appsecret',
  `wxid` varchar(64) NOT NULL COMMENT '公众号原始ID',
  `weixin` char(64) NOT NULL COMMENT '微信号',
  `token` char(255) NOT NULL COMMENT 'token',
  `w_token` varchar(150) NOT NULL DEFAULT '' COMMENT '微信对接token',
  `create_time` int(11) NOT NULL COMMENT 'create_time',
  `updatetime` int(11) NOT NULL COMMENT 'updatetime',
  `tplcontentid` varchar(2) NOT NULL COMMENT '内容模版ID',
  `share_ticket` varchar(150) NOT NULL COMMENT '分享ticket',
  `share_dated` char(15) NOT NULL COMMENT 'share_dated',
  `authorizer_access_token` varchar(200) NOT NULL COMMENT 'authorizer_access_token',
  `authorizer_refresh_token` varchar(200) NOT NULL COMMENT 'authorizer_refresh_token',
  `authorizer_expires` char(10) NOT NULL COMMENT 'authorizer_expires',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型',
  `web_access_token` varchar(200) NOT NULL COMMENT '网页授权token',
  `web_refresh_token` varchar(200) NOT NULL COMMENT 'web_refresh_token',
  `web_expires` int(11) NOT NULL COMMENT '过期时间',
  `menu_config` text COMMENT '菜单',
  `wait_access` tinyint(1) DEFAULT '0' COMMENT '微信接入状态,0待接入1已接入',
  `concern` varchar(225) DEFAULT '' COMMENT '关注回复',
  `default` varchar(225) DEFAULT '' COMMENT '默认回复',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `uid_2` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='微信公共帐号';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_user_msg`
--

DROP TABLE IF EXISTS `wx_user_msg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_user_msg` (
  `msg_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `msg_type` varchar(255) NOT NULL,
  `content` text,
  `is_replay` int(11) NOT NULL DEFAULT '0' COMMENT '是否回复',
  `create_time` int(11) DEFAULT '0',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户消息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `wx_user_msg_replay`
--

DROP TABLE IF EXISTS `wx_user_msg_replay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wx_user_msg_replay` (
  `replay_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) NOT NULL,
  `replay_uid` int(11) NOT NULL COMMENT '当前客服uid',
  `replay_type` varchar(255) NOT NULL,
  `content` text,
  `replay_time` int(11) DEFAULT '0',
  PRIMARY KEY (`replay_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户消息回复表';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-10 16:50:05
