CREATE DATABASE question_chinese DEFAULT CHARSET=utf8;
CREATE DATABASE question_math DEFAULT CHARSET=utf8;
CREATE DATABASE question_english DEFAULT CHARSET=utf8;
CREATE DATABASE question_physical DEFAULT CHARSET=utf8;
CREATE DATABASE question_chemistry DEFAULT CHARSET=utf8;
CREATE DATABASE question_biological DEFAULT CHARSET=utf8;
CREATE DATABASE question_political DEFAULT CHARSET=utf8;
CREATE DATABASE question_history DEFAULT CHARSET=utf8;
CREATE DATABASE question_geography DEFAULT CHARSET=utf8;
CREATE DATABASE question_common_technology DEFAULT CHARSET=utf8;
CREATE DATABASE question_internet_technology DEFAULT CHARSET=utf8;
use question_chinese;

question_chinese
question_math
question_english
question_physical
question_chemistry
question_biological
question_political
question_history
question_geography
question_common_technology
question_internet_technology

CREATE TABLE `question_record` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
  `uuid` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '业务唯一ID',
  `question_md5` VARCHAR(40) DEFAULT '' COMMENT '去重的md5',
  `question_creator_id` VARCHAR(100) DEFAULT '' COMMENT '创建问题人的ID',
  `question_remark` TEXT COMMENT '问题的附件',
  `work_status` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '问题状态 0:待拆解, 1:拆解中, 2:拆解完成',
  `work_content` TEXT COMMENT '拆解用到的参数',
  `del_status` BOOLEAN DEFAULT FALSE COMMENT '逻辑删除状态,false:未删除, true:已删除',
  `create_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uuid` (`uuid`),
  KEY `idx_question_creator_id` (`question_creator_id`),
  KEY `idx_work_status` (`work_status`),
  KEY `idx_question_md5` (`question_md5`),
  KEY `idx_del_status` (`del_status`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '问题详情表';

CREATE TABLE `question_detail` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
  `uuid` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '业务唯一ID',
  `question_record_id` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '问题记录ID',
  `question_content` TEXT COMMENT '问题的正文',
  `question_answer` TEXT COMMENT '问题的答案',
  `question_analysis` TEXT COMMENT '问题的分析',
  `question_knowledge_point` TEXT COMMENT '问题的知识点',
  `question_question_point` TEXT COMMENT '问题的题点',
  `grade` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '学段',
  `subject` INT(11) NOT NULL DEFAULT 0 COMMENT '科目',
  `version` INT(11) NOT NULL DEFAULT 0 COMMENT '教材版本',
  `module` INT(11) NOT NULL DEFAULT 0 COMMENT '教材模块',
  `question_type` INT(11) NOT NULL DEFAULT 0 COMMENT '题型',
  `difficulty` TINYINT(4) NOT NULL DEFAULT 0 COMMENT '难度',
  `del_status` BOOLEAN DEFAULT FALSE COMMENT '逻辑删除状态,false:未删除, true:已删除',
  `create_time` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uuid` (`uuid`),
  KEY `idx_question_record_id` (`question_record_id`),
  KEY `idx_grade` (`grade`),
  KEY `idx_subject` (`subject`),
  KEY `idx_version` (`version`),
  KEY `idx_module` (`module`),
  KEY `idx_question_type` (`question_type`),
  KEY `idx_difficulty` (`difficulty`),
  KEY `idx_del_status` (`del_status`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '问题详情表';

CREATE TABLE `question_math`.`question_record` LIKE `question_record`;
CREATE TABLE `question_english`.`question_record` LIKE `question_record`;
CREATE TABLE `question_physical`.`question_record` LIKE `question_record`;
CREATE TABLE `question_chemistry`.`question_record` LIKE `question_record`;
CREATE TABLE `question_biological`.`question_record` LIKE `question_record`;
CREATE TABLE `question_political`.`question_record` LIKE `question_record`;
CREATE TABLE `question_history`.`question_record` LIKE `question_record`;
CREATE TABLE `question_geography`.`question_record` LIKE `question_record`;
CREATE TABLE `question_common_technology`.`question_record` LIKE `question_record`;
CREATE TABLE `question_internet_technology`.`question_record` LIKE `question_record`;

CREATE TABLE `question_math`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_english`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_physical`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_chemistry`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_biological`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_political`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_history`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_geography`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_common_technology`.`question_detail` LIKE `question_detail`;
CREATE TABLE `question_internet_technology`.`question_detail` LIKE `question_detail`;

grant select,insert,update on question_chinese.* to 'question_rd'@'%';
grant select,insert,update on question_math.* to 'question_rd'@'%';
grant select,insert,update on question_english.* to 'question_rd'@'%';
grant select,insert,update on question_physical.* to 'question_rd'@'%';
grant select,insert,update on question_chemistry.* to 'question_rd'@'%';
grant select,insert,update on question_biological.* to 'question_rd'@'%';
grant select,insert,update on question_political.* to 'question_rd'@'%';
grant select,insert,update on question_history.* to 'question_rd'@'%';
grant select,insert,update on question_geography.* to 'question_rd'@'%';
grant select,insert,update on question_common_technology.* to 'question_rd'@'%';
grant select,insert,update on question_internet_technology.* to 'question_rd'@'%';