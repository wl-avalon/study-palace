CREATE DATABASE question_const DEFAULT CHARSET=utf8;
use question_const;

CREATE TABLE `grade_enum`(
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
  `tag_key` BIGINT(20) DEFAULT 0 COMMENT 'tag-key',
  `tag_value` BIGINT(20) DEFAULT 0 COMMENT 'tag-value',
  PRIMARY KEY (`id`),
  KEY `idx_tag_key` (`tag_key`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '年级枚举';

CREATE TABLE `subject_enum`(
  `id` BIGINT(20) NOT NULL  AUTO_INCREMENT COMMENT '主键,自增ID',
  `tag_key` BIGINT(20) DEFAULT 0 COMMENT 'tag-key',
  `tag_value` BIGINT(20) DEFAULT 0 COMMENT 'tag-value',
  PRIMARY KEY (`id`),
  KEY `idx_tag_key` (`tag_key`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '科目枚举';

CREATE TABLE `teaching_material_version_enum`(
  `id` BIGINT(20) NOT NULL  AUTO_INCREMENT COMMENT '主键,自增ID',
  `tag_key` BIGINT(20) DEFAULT 0 COMMENT 'tag-key',
  `tag_value` BIGINT(20) DEFAULT 0 COMMENT 'tag-value',
  PRIMARY KEY (`id`),
  KEY `idx_tag_key` (`tag_key`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '教材版本枚举';

CREATE TABLE `module_level_enum`(
  `id` BIGINT(20) NOT NULL  AUTO_INCREMENT COMMENT '主键,自增ID',
  `tag_key` BIGINT(20) DEFAULT 0 COMMENT 'tag-key',
  `tag_value` BIGINT(20) DEFAULT 0 COMMENT 'tag-value',
  PRIMARY KEY (`id`),
  KEY `idx_tag_key` (`tag_key`)
)ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '模块枚举';

grant select,insert,update on question_const.* to 'quest_const_rd'@'%';