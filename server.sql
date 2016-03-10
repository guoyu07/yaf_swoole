DROP TABLE IF EXISTS `conf`;
CREATE TABLE `conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '配置名称',
  `val` text COMMENT '配置内容',
  `remark` varchar(200) DEFAULT NULL COMMENT '配置备注',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录创建时间',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '配置类型，1是站点配置',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='网站配置表';


LOCK TABLES `conf` WRITE;
INSERT INTO `conf` VALUES (1,'site_name','测试网站名称','网站名称','2016-03-04 07:24:19',1);
UNLOCK TABLES;

CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `un` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `pw` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录生成时间',
  `lastlogin` timestamp NULL DEFAULT NULL COMMENT '最后一次登录时间',
  `lastip` char(15) DEFAULT NULL COMMENT '最后一次登录IP',
  `isvalid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有效，1是，0否，默认0',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `islock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否锁定，1是，0否，默认0',
  `utime` timestamp NULL DEFAULT NULL COMMENT '记录最后一次更新时间',
  `qq` varchar(20) DEFAULT NULL COMMENT 'qq号码',
  `mobile` char(11) DEFAULT NULL COMMENT '手机号码',
  `email` varchar(30) DEFAULT NULL COMMENT '邮箱',
  `avatar` varchar(200) DEFAULT NULL COMMENT '头像',
  PRIMARY KEY (`id`),
  UNIQUE KEY `un` (`un`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

