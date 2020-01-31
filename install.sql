/* Создание таблицы в базе данных */
CREATE TABLE IF NOT EXISTS `oc_tg_users`
(
    `user_id`    int(11)                                                NOT NULL,
    `first_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `last_name`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `username`   varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `telephone`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_chats`
(
    `user_id` int(11) NOT NULL,
    `chat_id` int(11) NOT NULL,
    PRIMARY KEY (`user_id`, `chat_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_variables_type`
(
    `type_id`   int(11)                                                NOT NULL AUTO_INCREMENT,
    `type_name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`type_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_variables`
(
    `variables_id` int(11)                                                NOT NULL AUTO_INCREMENT,
    `name`         varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `value`        longtext CHARACTER SET utf8 COLLATE utf8_general_ci    NOT NULL,
    `type_id`      int(11)                                                NOT NULL,
    `serialized`   tinyint(1),
    PRIMARY KEY (`variables_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_inline_keybord_button`
(
    `inline_button_id` int(11)                                                NOT NULL AUTO_INCREMENT,
    `name`             varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    `variables_id`     int(11)                                                NOT NULL,
    PRIMARY KEY (`inline_button_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_inline_keybord_description`
(
    `inline_keybord_id` int(11)                                                 NOT NULL,
    `name`              varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL,
    `description`       varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`inline_keybord_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_inline_keybord`
(
    `inline_keybord_id` int(11) NOT NULL AUTO_INCREMENT,
    `Inline_button_id`  int(11) NOT NULL,
    PRIMARY KEY (`inline_keybord_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_keyboard_element`
(
    `keyboard_element_id` int(11)                                                NOT NULL AUTO_INCREMENT,
    `text`                varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`keyboard_element_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_keyboard`
(
    `keyboard_id`         int(11)                                                 NOT NULL AUTO_INCREMENT,
    `keyboard_element_id` int(11)                                                 NOT NULL,
    `description`         varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`keyboard_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_action`
(
    `action_id`   int(11)                                                 NOT NULL AUTO_INCREMENT,
    `method`      varchar(96) CHARACTER SET utf8 COLLATE utf8_general_ci  NOT NULL,
    `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`action_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `oc_tg_cron`
(
    `cron_id`     int(11)                                                NOT NULL AUTO_INCREMENT,
    `chat_id`     int(11)                                                NOT NULL,
    `action_id`   int(11)                                                NOT NULL,
    `date_add`    datetime,
    `date_update` datetime,
    `status`      tinyint(1)                                             NOT NULL,
    `email`       varchar(96) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
    PRIMARY KEY (`cron_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;



CREATE TABLE `oc_tg_inline_keyboard`
(
    `inline_keyboard_id` INTEGER(11) NULL AUTO_INCREMENT DEFAULT NULL,
    `inline_button_id`   INTEGER(11) NULL                DEFAULT NULL,
    PRIMARY KEY (`inline_keyboard_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


CREATE TABLE `oc_tg_inline_keyboard_button`
(
    `inline_button_id` INTEGER(11) NULL AUTO_INCREMENT                        DEFAULT NULL,
    `name`             VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
    `variables_id`     INTEGER(11) NULL                                       DEFAULT NULL,
    PRIMARY KEY (`inline_button_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `oc_tg_inline_keyboard_description`
(
    `name`               VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci  NULL DEFAULT NULL,
    `description`        VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
    `inline_keyboard_id` INTEGER(11)  NULL DEFAULT NULL
)ENGINE = InnoDB
 DEFAULT CHARSET = utf8;

# ALTER TABLE `oc_tg_chats`
#     ADD FOREIGN KEY (user_id) REFERENCES `oc_tg_users` (`user_id`); Ошибки вылезли
ALTER TABLE `oc_tg_variables`
    ADD FOREIGN KEY (type_id) REFERENCES `oc_tg_variables_type` (`type_id`);
ALTER TABLE `oc_tg_cron`
    ADD FOREIGN KEY (action_id) REFERENCES `oc_tg_action` (`action_id`);
# ALTER TABLE `oc_tg_cron`
#     ADD FOREIGN KEY (chat_id) REFERENCES `oc_tg_chats` (`chat_id`); Ошибки вылезли
ALTER TABLE `oc_tg_inline_keyboard`
    ADD FOREIGN KEY (inline_button_id) REFERENCES `oc_tg_inline_keyboard_button` (`inline_button_id`);
ALTER TABLE `oc_tg_inline_keyboard_button`
    ADD FOREIGN KEY (variables_id) REFERENCES `oc_tg_variables` (`variables_id`);
ALTER TABLE `oc_tg_keyboard`
    ADD FOREIGN KEY (keyboard_element_id) REFERENCES `oc_tg_keyboard_element` (`keyboard_element_id`);
ALTER TABLE `oc_tg_inline_keyboard_description`
    ADD FOREIGN KEY (inline_keyboard_id) REFERENCES `oc_tg_inline_keyboard` (`inline_keyboard_id`);
