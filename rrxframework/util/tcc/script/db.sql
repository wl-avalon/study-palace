create database tccActivity;
use tccActivity;
##
create table tcc_transcation(
  `id`  bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `transcation_id` char(32) NOT NULL COMMENT '流程号ID',
  `decision_type` tinyint(4) DEFAULT NULL COMMENT '流程类型,0=>内部决策,1=>外部决策',
  `content` varchar(8000) DEFAULT NULL COMMENT '流程中间数据存储',
  `decision_content` varchar(8000) DEFAULT NULL COMMENT '决策数据存储',
  `control_class_name` varchar(64) DEFAULT NULL COMMENT '控制器类名字,用于恢复现场',
  `process_result` tinyint(4) DEFAULT NULL COMMENT '单次流程的处理结果态, 1/2/3 成功/失败/工单',
  `process_status` tinyint(4) DEFAULT NULL COMMENT '流程状态标识别,0=>start,1=>process,2=>end',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '流程启动时间',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '流程修改时间',
  PRIMARY KEY (`id`),
  unique key `transcation_id` (`transcation_id`),
  KEY `process_status` (`process_status`, `create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

