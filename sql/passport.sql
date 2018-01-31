CREATE DATABASE passport_0 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_1 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_2 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_3 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_4 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_5 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_6 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_7 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_8 DEFAULT CHARSET=utf8;
CREATE DATABASE passport_9 DEFAULT CHARSET=utf8;
use passport_0;

CREATE TABLE `passport_user` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '主键,自增ID',
  `uuid` VARCHAR(100) DEFAULT '' COMMENT '用户唯一ID',
  `user_type` TINYINT(4) DEFAULT 0 COMMENT '用户类型 0:超级管理员',
  `user_status` TINYINT(4) DEFAULT 0 COMMENT '用户状态 0:预注册 1:正常，2:挂失(临时不用) 3:注销(永久不用)',
  `phone` VARCHAR(30) DEFAULT '' COMMENT '手机号',
  `nick_name` VARCHAR(30) DEFAULT '' COMMENT '昵称',
  `avatar_url` VARCHAR(300) DEFAULT '' COMMENT '头像地址',
  `register_time` TIMESTAMP DEFAULT '0000-00-00 00:00:00' COMMENT '注册时间',
  `create_time` TIMESTAMP DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` TIMESTAMP DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uuid` (`uuid`),
  KEY `idx_user_type` (`user_type`),
  KEY `idx_user_status` (`user_status`),
  KEY `idx_phone` (`phone`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '用户记录主表';

CREATE TABLE `passport_1`.passport_user LIKE `passport_user`;
CREATE TABLE `passport_2`.passport_user LIKE `passport_user`;
CREATE TABLE `passport_3`.passport_user LIKE `passport_user`;
CREATE TABLE `passport_4`.passport_user LIKE `passport_user`;
CREATE TABLE `passport_5`.passport_user LIKE `passport_user`;
CREATE TABLE `passport_6`.passport_user LIKE `passport_user`;
CREATE TABLE `passport_7`.passport_user LIKE `passport_user`;
CREATE TABLE `passport_8`.passport_user LIKE `passport_user`;
CREATE TABLE `passport_9`.passport_user LIKE `passport_user`;

grant select,insert,update on passport_0.* to 'passport_rd'@'%';
grant select,insert,update on passport_1.* to 'passport_rd'@'%';
grant select,insert,update on passport_2.* to 'passport_rd'@'%';
grant select,insert,update on passport_3.* to 'passport_rd'@'%';
grant select,insert,update on passport_4.* to 'passport_rd'@'%';
grant select,insert,update on passport_5.* to 'passport_rd'@'%';
grant select,insert,update on passport_6.* to 'passport_rd'@'%';
grant select,insert,update on passport_7.* to 'passport_rd'@'%';
grant select,insert,update on passport_8.* to 'passport_rd'@'%';
grant select,insert,update on passport_9.* to 'passport_rd'@'%';