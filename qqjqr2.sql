

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for qq_info
-- ----------------------------
DROP TABLE IF EXISTS `qq_info`;
CREATE TABLE `qq_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qq_number` char(15) NOT NULL COMMENT 'qq号/qq群号',
  `qq_name` varchar(255) DEFAULT NULL COMMENT 'qq名，群名',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'qq号类型 1-群，2-qq号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'qq状态使用状态 1-正常 2-冻结 3-待激活 0-黑名单 ',
  `create_time` int(10) NOT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COMMENT='存放qq,群的状态信息';

-- ----------------------------
-- Table structure for qq_msg_log
-- ----------------------------
DROP TABLE IF EXISTS `qq_msg_log`;
CREATE TABLE `qq_msg_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` varchar(20) DEFAULT NULL COMMENT '群号',
  `group_name` varchar(255) DEFAULT NULL COMMENT '群名',
  `qq_id` varchar(20) NOT NULL COMMENT 'QQ号',
  `qq_nick` varchar(255) DEFAULT NULL COMMENT 'qq昵称',
  `message` varchar(1000) NOT NULL COMMENT '消息内容',
  `create_time` int(10) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2105158 DEFAULT CHARSET=utf8 COMMENT='qq消息记录';

-- ----------------------------
-- Table structure for qq_push
-- ----------------------------
DROP TABLE IF EXISTS `qq_push`;
CREATE TABLE `qq_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL COMMENT '推送内容',
  `qq_type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '推送类型 1-QQ群 2-QQ',
  `to_qq` varchar(30) NOT NULL COMMENT 'qq推送目标',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推送状态 0-未推送 1-已推送',
  `create_time` int(10) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=544 DEFAULT CHARSET=utf8 COMMENT='QQ消息推送表';

-- ----------------------------
-- Table structure for qq_qa
-- ----------------------------
DROP TABLE IF EXISTS `qq_qa`;
CREATE TABLE `qq_qa` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '问答记录',
  `question` varchar(500) NOT NULL COMMENT '提问',
  `answer` varchar(500) NOT NULL COMMENT '回复',
  `create_time` int(10) NOT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17426 DEFAULT CHARSET=utf8 COMMENT='问答记录';
