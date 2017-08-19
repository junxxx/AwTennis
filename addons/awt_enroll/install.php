<?php
/**
 * File  install.php
 * Created by https://github.com/junxxx
 * Email: hprjunxxx@gmail.com
 * Author: junxxx
 * Datetime: 2017/8/13 12:07
 */
$sql = "
CREATE TABLE IF NOT EXISTS `awt_enroll_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `detail` text NOT NULL,
  `is_display` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不显示,1显示',
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `awt_enroll_activities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(45) NOT NULL COMMENT '标题',
  `headimg` varchar(255) NOT NULL COMMENT '活动封面图',
  `cid` int(11) unsigned NOT NULL COMMENT '活动分类id,如单,双,混,拉球...',
  `sign_stime` int(10) NOT NULL COMMENT '开始报名时间,时间戳',
  `activity_stime` int(10) NOT NULL COMMENT '活动开始时间,时间戳',
  `location` varchar(100) NOT NULL COMMENT '活动地点',
  `rule` text NOT NULL COMMENT '活动细则,比赛规则',
  `com_nums` int(11) NOT NULL COMMENT '正选人数',
  `fee` decimal(10,2) NOT NULL COMMENT '活动费用',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `displayorder` int(11) NOT NULL COMMENT '排序',
  `createtime` int(11) NOT NULL,
  `updatetime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='爱网活动表';


CREATE TABLE IF NOT EXISTS `awt_enroll_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `groupid` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `realname` varchar(20) DEFAULT '',
  `mobile` varchar(11) DEFAULT '',
  `pwd` varchar(20) DEFAULT '',
  `weixin` varchar(100) DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `nickname` varchar(255) DEFAULT '',
  `credit1` int(11) DEFAULT '0',
  `credit2` decimal(10,2) DEFAULT '0.00',
  `birthyear` varchar(255) DEFAULT '',
  `birthmonth` varchar(255) DEFAULT '',
  `birthday` varchar(255) DEFAULT '',
  `gender` tinyint(3) DEFAULT '0',
  `avatar` varchar(255) DEFAULT '',
  `province` varchar(255) DEFAULT '',
  `city` varchar(255) DEFAULT '',
  `area` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_status` (`status`),
  KEY `idx_uid` (`uid`),
  KEY `idx_groupid` (`groupid`),
  KEY `idx_level` (`level`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS  `awt_enroll_activitie_logs` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uniacid` INT(11) NOT NULL,
  `aid` INT(11) NOT NULL COMMENT '活动id',
  `mid` INT(11) NOT NULL COMMENT 'enroll_member id',
  `openid` VARCHAR(50) NOT NULL,
  `createtime` INT(10) NOT NULL COMMENT '报名提交时间',
  `status` TINYINT(1) NOT NULL DEFAULT 0  COMMENT '报名状态',
  `updatetime` INT(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
COMMENT = '活动报名记录';

";

pdo_query($sql);
