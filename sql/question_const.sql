CREATE DATABASE question_const DEFAULT CHARSET=utf8;
use question_const;
grant select,insert,update on question_const.* to 'quest_const_rd'@'%';

CREATE TABLE `node_list`(
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
  `uuid` VARCHAR(40) NOT NULL DEFAULT '' COMMENT '唯一nodeID',
  `grade` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '学段',
  `subject` INT(11) NOT NULL DEFAULT 0 COMMENT '学科',
  `version` INT(11) NOT NULL DEFAULT 0 COMMENT '教材版本',
  `module` INT(11) NOT NULL DEFAULT 0 COMMENT '模块',
  `node_key` INT(11) NOT NULL DEFAULT 0 COMMENT '节点key',
  `node_value` VARCHAR(40) NOT NULL DEFAULT 0 COMMENT '节点值',
  `parent_node_id` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '父级节点ID',
  `del_status` BOOLEAN DEFAULT FALSE COMMENT '逻辑删除状态,false:未删除, true:已删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uuid` (`uuid`),
  KEY `idx_grade_subject_version_module` (`grade`, `subject`, `version`, `module`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '节点列表';