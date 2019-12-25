-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'oc_tg_chats'
-- Хранение открытых чатов в телеграмме
-- ---

DROP TABLE IF EXISTS `oc_tg_chats`;

CREATE TABLE `oc_tg_chats` (
  `chat_id` INTEGER(11) NULL DEFAULT NULL COMMENT 'chat_id on telegramm',
  `user_id` INTEGER(11) NULL DEFAULT NULL COMMENT 'user_id in telegramm',
KEY (`chat_id`, `user_id`)
) COMMENT 'Хранение открытых чатов в телеграмме';

-- ---
-- Table 'oc_tg_variables'
-- variables for telegramm
-- ---

DROP TABLE IF EXISTS `oc_tg_variables`;

CREATE TABLE `oc_tg_variables` (
  `variables_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(32) NULL DEFAULT NULL,
  `value` MEDIUMTEXT(255) NULL DEFAULT NULL,
  `serialize` TINYINT(1) NULL DEFAULT NULL,
  `type_id` INTEGER(11) NULL DEFAULT NULL,
  PRIMARY KEY (`variables_id`)
) COMMENT 'variables for telegramm';

-- ---
-- Table 'oc_tg_cron'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_cron`;

CREATE TABLE `oc_tg_cron` (
  `cron_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
  `date_add` DATETIME NULL DEFAULT NULL,
  `date_update` DATETIME NULL DEFAULT NULL,
  `status` TINYINT(1) NULL DEFAULT NULL,
  `email` VARCHAR(96) NULL DEFAULT NULL,
  `action_id` INTEGER(11) NULL DEFAULT NULL,
  `chat_id` INTEGER(11) NULL DEFAULT NULL COMMENT 'chat_id on telegramm',
  PRIMARY KEY (`cron_id`)
);

-- ---
-- Table 'oc_tg_inline_keyboard'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_inline_keyboard`;

CREATE TABLE `oc_tg_inline_keyboard` (
  `inline_keyboard_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
  `inline_button_id` INTEGER(11) NULL DEFAULT NULL,
  PRIMARY KEY (`inline_keyboard_id`)
);

-- ---
-- Table 'oc_tg_inline_keyboard_button'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_inline_keyboard_button`;

CREATE TABLE `oc_tg_inline_keyboard_button` (
  `inline_button_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(96) NULL DEFAULT NULL,
  `variables_id` INTEGER(11) NULL DEFAULT NULL,
  PRIMARY KEY (`inline_button_id`)
);

-- ---
-- Table 'oc_tg_users'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_users`;

CREATE TABLE `oc_tg_users` (
  `user_id` INTEGER(11) NULL DEFAULT NULL COMMENT 'user_id in telegramm',
  `first_name` VARCHAR(32) NULL DEFAULT NULL COMMENT 'first_name user in telegramm',
  `last_name` VARCHAR(32) NULL DEFAULT NULL COMMENT 'last_name user in telegramm',
  `username` VARCHAR(32) NULL DEFAULT NULL COMMENT 'username user in telegramm',
  `telephone` VARCHAR(32) NULL DEFAULT NULL COMMENT 'telephone user in telegramm if share',
  PRIMARY KEY (`user_id`)
);

-- ---
-- Table 'oc_tg_variables_type'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_variables_type`;

CREATE TABLE `oc_tg_variables_type` (
  `type_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
  `type_name` VARCHAR(32) NULL DEFAULT NULL,
  PRIMARY KEY (`type_id`)
);

-- ---
-- Table 'oc_tg_action'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_action`;

CREATE TABLE `oc_tg_action` (
  `action_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
  `method` VARCHAR(96) NULL DEFAULT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`action_id`)
);

-- ---
-- Table 'oc_tg_keyboard_element'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_keyboard_element`;

CREATE TABLE `oc_tg_keyboard_element` (
  `keyboard_element_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
  `text` VARCHAR(32) NULL DEFAULT NULL,
  PRIMARY KEY (`keyboard_element_id`)
);

-- ---
-- Table 'oc_tg_keyboard'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_keyboard`;

CREATE TABLE `oc_tg_keyboard` (
  `keyboard_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `keyboard_element_id` INTEGER(11) NULL DEFAULT NULL,
  PRIMARY KEY (`keyboard_id`)
);

-- ---
-- Table 'oc_tg_inline_keyboard_description'
--
-- ---

DROP TABLE IF EXISTS `oc_tg_inline_keyboard_description`;

CREATE TABLE `oc_tg_inline_keyboard_description` (
  `name` VARCHAR(32) NULL DEFAULT NULL,
  `description` VARCHAR(255) NULL DEFAULT NULL,
  `inline_keyboard_id` INTEGER(11) NULL DEFAULT NULL
);

-- ---
-- Foreign Keys
-- ---

ALTER TABLE `oc_tg_chats` ADD FOREIGN KEY (user_id) REFERENCES `oc_tg_users` (`user_id`);
ALTER TABLE `oc_tg_variables` ADD FOREIGN KEY (type_id) REFERENCES `oc_tg_variables_type` (`type_id`);
ALTER TABLE `oc_tg_cron` ADD FOREIGN KEY (action_id) REFERENCES `oc_tg_action` (`action_id`);
ALTER TABLE `oc_tg_cron` ADD FOREIGN KEY (chat_id) REFERENCES `oc_tg_chats` (`chat_id`);
ALTER TABLE `oc_tg_inline_keyboard` ADD FOREIGN KEY (inline_button_id) REFERENCES `oc_tg_inline_keyboard_button` (`inline_button_id`);
ALTER TABLE `oc_tg_inline_keyboard_button` ADD FOREIGN KEY (variables_id) REFERENCES `oc_tg_variables` (`variables_id`);
ALTER TABLE `oc_tg_keyboard` ADD FOREIGN KEY (keyboard_element_id) REFERENCES `oc_tg_keyboard_element` (`keyboard_element_id`);
ALTER TABLE `oc_tg_inline_keyboard_description` ADD FOREIGN KEY (inline_keyboard_id) REFERENCES `oc_tg_inline_keyboard` (`inline_keyboard_id`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `oc_tg_chats` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_variables` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_cron` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_inline_keyboard` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_inline_keyboard_button` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_variables_type` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_action` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_keyboard_element` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_keyboard` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `oc_tg_inline_keyboard_description` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `oc_tg_chats` (`chat_id`,`user_id`) VALUES
-- ('','');
-- INSERT INTO `oc_tg_variables` (`variables_id`,`name`,`value`,`serialize`,`type_id`) VALUES
-- ('','','','','');
-- INSERT INTO `oc_tg_cron` (`cron_id`,`date_add`,`date_update`,`status`,`email`,`action_id`,`chat_id`) VALUES
-- ('','','','','','','');
-- INSERT INTO `oc_tg_inline_keyboard` (`inline_keyboard_id`,`inline_button_id`) VALUES
-- ('','');
-- INSERT INTO `oc_tg_inline_keyboard_button` (`inline_button_id`,`name`,`variables_id`) VALUES
-- ('','','');
-- INSERT INTO `oc_tg_users` (`user_id`,`first_name`,`last_name`,`username`,`telephone`) VALUES
-- ('','','','','');
-- INSERT INTO `oc_tg_variables_type` (`type_id`,`type_name`) VALUES
-- ('','');
-- INSERT INTO `oc_tg_action` (`action_id`,`method`,`description`) VALUES
-- ('','','');
-- INSERT INTO `oc_tg_keyboard_element` (`keyboard_element_id`,`text`) VALUES
-- ('','');
-- INSERT INTO `oc_tg_keyboard` (`keyboard_id`,`description`,`keyboard_element_id`) VALUES
-- ('','','');
-- INSERT INTO `oc_tg_inline_keyboard_description` (`name`,`description`,`inline_keyboard_id`) VALUES
-- ('','','');