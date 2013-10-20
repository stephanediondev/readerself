#2013-08-24
ALTER TABLE `history` ADD `hst_real` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `itm_id`;
ALTER TABLE `history` ADD INDEX ( `hst_real` );
ALTER TABLE `subscriptions` ADD `sub_title` VARCHAR( 255 ) NULL AFTER `tag_id`;

#2013-08-25
ALTER TABLE `feeds` ADD `fed_image` VARCHAR( 255 ) NULL AFTER `fed_link`;
CREATE TABLE IF NOT EXISTS `enclosures` (
  `enr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `itm_id` bigint(20) unsigned NOT NULL,
  `enr_link` varchar(255) NOT NULL,
  `enr_type` varchar(255) NOT NULL,
  `enr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`enr_id`),
  KEY `itm_id` (`itm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
ALTER TABLE `feeds` CHANGE `fed_title` `fed_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
CHANGE `fed_url` `fed_url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `feeds` DROP INDEX `fed_lasterror`;

#2013-08-29
RENAME TABLE `tags` TO `folders`;
ALTER TABLE `folders` CHANGE `tag_id` `flr_id` BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
CHANGE `tag_title` `flr_title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
CHANGE `tag_datecreated` `flr_datecreated` DATETIME NOT NULL;
ALTER TABLE `subscriptions` CHANGE `tag_id` `flr_id` BIGINT( 20 ) UNSIGNED NULL DEFAULT NULL;
CREATE TABLE IF NOT EXISTS `share` (
  `shr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `itm_id` bigint(20) unsigned NOT NULL,
  `shr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`shr_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `itm_id` (`itm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

#2013-08-30
ALTER TABLE `enclosures` ADD `enr_length` INT UNSIGNED NOT NULL AFTER `enr_type`;
ALTER TABLE `members` ADD `token_share` CHAR( 40 ) NULL DEFAULT NULL AFTER `token_password`;
CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `itm_id` bigint(20) unsigned NOT NULL,
  `cat_title` varchar(255) NOT NULL,
  `cat_datecreated` datetime NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `itm_id` (`itm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
ALTER TABLE `feeds` ADD `fed_lastcrawl` DATETIME NULL DEFAULT NULL AFTER `fed_lasterror`;
ALTER TABLE `feeds` ADD `fed_nextcrawl` DATETIME NULL DEFAULT NULL AFTER `fed_lastcrawl`;

#2013-09-01
CREATE TABLE IF NOT EXISTS `crawler` (
  `crr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crr_time` double unsigned NOT NULL,
  `crr_count` int(10) unsigned NOT NULL,
  `crr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`crr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

#2013-09-08
ALTER TABLE `items` ADD `itm_latitude` DOUBLE NULL AFTER `itm_content` ,
ADD `itm_longitude` DOUBLE NULL AFTER `itm_latitude`;

#2013-09-11
DELETE FROM `connections` WHERE `token_connection` IS NULL;
ALTER TABLE `enclosures` CHANGE `enr_length` `enr_length` INT( 10 ) UNSIGNED NULL;
ALTER TABLE `enclosures` ADD `enr_width` INT UNSIGNED NULL AFTER `enr_length`;
ALTER TABLE `enclosures` ADD `enr_height` INT UNSIGNED NULL AFTER `enr_width`;

#2013-09-12
ALTER TABLE `crawler` ADD `crr_memory` INT UNSIGNED NULL AFTER `crr_time`;

#2013-09-15
ALTER TABLE `subscriptions` ADD `sub_priority` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `sub_title`;

#2013-09-16
ALTER TABLE `subscriptions` ADD `sub_direction` CHAR( 3 ) NULL AFTER `sub_priority`;

#2013-09-17
ALTER TABLE `feeds` ADD `fed_direction` CHAR( 3 ) NULL AFTER `fed_description`;
ALTER TABLE `folders` ADD `flr_direction` CHAR( 3 ) NULL AFTER `flr_title`;

#2013-09-19
ALTER TABLE `feeds` ADD `fed_type` ENUM( 'rss', 'atom' ) NULL AFTER `fed_link`;

#2013-09-22
ALTER TABLE `members` ADD `mbr_nickname` VARCHAR( 255 ) NULL AFTER `mbr_password`;
ALTER TABLE `members` ADD UNIQUE (`mbr_nickname`);
ALTER TABLE `members` ADD `mbr_description` TEXT NULL AFTER `mbr_nickname`;
ALTER TABLE `members` ADD `mbr_gravatar` VARCHAR( 255 ) NULL AFTER `mbr_nickname`;

#2013-10-05
ALTER TABLE `feeds` ADD `fed_host` VARCHAR( 255 ) NULL AFTER `fed_link`;

#2013-10-06
ALTER TABLE `members` ADD `mbr_administrator` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `mbr_description`;
UPDATE `members` SET `mbr_administrator` = '1' WHERE 1 LIMIT 1;

#2013-10-07
ALTER TABLE `members` ADD `token_msapplication` CHAR( 40 ) NULL AFTER `token_share`;

#2013-10-12
CREATE TABLE IF NOT EXISTS `settings` (
  `stg_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stg_code` varchar(255) NOT NULL,
  `stg_type` varchar(255) NOT NULL,
  `stg_value` varchar(255) DEFAULT NULL,
  `stg_note` varchar(255) DEFAULT NULL,
  `stg_is_global` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stg_is_member` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stg_is_subscription` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stg_datecreated` datetime NOT NULL,
  PRIMARY KEY (`stg_id`),
  UNIQUE KEY `stg_code` (`stg_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`stg_id`, `stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
(1, 'folders', 'boolean', '1', NULL, 1, 0, 0, '2013-10-12 21:35:22'),
(2, 'gravatar', 'boolean', '1', NULL, 1, 0, 0, '2013-10-12 21:35:25'),
(3, 'gravatar_default', 'string', 'identicon', 'identicon, mm, monsterid, retro, wavatar', 1, 1, 0, '2013-10-12 21:35:51'),
(4, 'gravatar_rating', 'string', 'pg', 'g, pg, r, x', 1, 1, 0, '2013-10-12 21:36:06'),
(5, 'gravatar_size', 'integer', '70', NULL, 1, 0, 0, '2013-10-12 21:36:21'),
(6, 'menu_geolocation_items', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:36:36'),
(7, 'menu_audio_items', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:36:49'),
(8, 'menu_video_items', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:37:01'),
(9, 'readability_parser_key', 'string', NULL, NULL, 1, 1, 0, '2013-10-12 21:37:14'),
(10, 'sender_email', 'email', 'mailer@readerself.com', NULL, 1, 0, 0, '2013-10-12 21:37:42'),
(11, 'sender_name', 'string', 'Reader Self', NULL, 1, 0, 0, '2013-10-12 21:37:56'),
(12, 'shared_items', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:38:11'),
(13, 'share_external_email', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:38:21'),
(14, 'social_buttons', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:38:38'),
(15, 'starred_items', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:38:51'),
(16, 'tags', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:39:09'),
(17, 'share_external', 'boolean', '1', NULL, 1, 1, 0, '2013-10-12 21:50:01'),
(18, 'title', 'string', 'Reader Self', NULL, 1, 0, 0, '2013-10-12 22:06:44'),
(19, 'members_list', 'boolean', '0', NULL, 1, 0, 0, '2013-10-12 22:21:19'),
(20, 'register_multi', 'boolean', '0', NULL, 1, 0, 0, '2013-10-12 22:21:22');

#2013-10-13
INSERT INTO `settings` (`stg_id`, `stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
(21, 'refresh_by_cron', 'boolean', '1', NULL, 1, 0, 0, '2013-10-13 20:48:44');

#2013-10-17
CREATE TABLE IF NOT EXISTS `followers` (
  `fws_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `fws_following` bigint(20) unsigned NOT NULL,
  `fws_datecreated` datetime NOT NULL,
  PRIMARY KEY (`fws_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `fws_following` (`fws_following`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

#2013-10-20
ALTER TABLE `crawler` CHANGE `crr_count` `crr_feeds` INT( 10 ) UNSIGNED NOT NULL;
ALTER TABLE `crawler` ADD `crr_errors` INT UNSIGNED NOT NULL AFTER `crr_feeds`;
