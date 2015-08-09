CREATE TABLE IF NOT EXISTS `authors` (
  `auh_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `auh_title` varchar(255) NOT NULL,
  `auh_datecreated` datetime NOT NULL,
  PRIMARY KEY (`auh_id`),
  UNIQUE KEY `auh_title` (`auh_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `itm_id` bigint(20) unsigned NOT NULL,
  `cat_title` varchar(255) NOT NULL,
  `cat_datecreated` datetime NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `itm_id` (`itm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `connections` (
  `cnt_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `token_connection` char(40) DEFAULT NULL,
  `cnt_ip` varchar(255) DEFAULT NULL,
  `cnt_agent` varchar(255) NOT NULL,
  `cnt_datecreated` datetime NOT NULL,
  PRIMARY KEY (`cnt_id`),
  UNIQUE KEY `token_connection` (`token_connection`),
  KEY `mbr_id` (`mbr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `crawler` (
  `crr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crr_time` double unsigned NOT NULL,
  `crr_memory` int(10) unsigned DEFAULT NULL,
  `crr_feeds` int(10) unsigned NOT NULL,
  `crr_errors` int(10) unsigned DEFAULT NULL,
  `crr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`crr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `elasticsearch_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `itm_id` bigint(20) unsigned NOT NULL,
  `datecreated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `itm_id` (`itm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `enclosures` (
  `enr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `itm_id` bigint(20) unsigned NOT NULL,
  `enr_link` varchar(255) NOT NULL,
  `enr_type` varchar(255) NOT NULL,
  `enr_length` int(10) unsigned DEFAULT NULL,
  `enr_width` int(10) unsigned DEFAULT NULL,
  `enr_height` int(10) unsigned DEFAULT NULL,
  `enr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`enr_id`),
  KEY `itm_id` (`itm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `favorites` (
  `fav_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `itm_id` bigint(20) unsigned NOT NULL,
  `fav_datecreated` datetime NOT NULL,
  PRIMARY KEY (`fav_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `itm_id` (`itm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `feeds` (
  `fed_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fed_title` varchar(255) DEFAULT NULL,
  `fed_url` varchar(255) DEFAULT NULL,
  `fed_link` varchar(255) NOT NULL,
  `fed_host` varchar(255) DEFAULT NULL,
  `fed_type` enum('rss','atom') DEFAULT NULL,
  `fed_image` varchar(255) DEFAULT NULL,
  `fed_description` text,
  `fed_direction` char(3) DEFAULT NULL,
  `fed_lasterror` varchar(255) DEFAULT NULL,
  `fed_lastcrawl` datetime DEFAULT NULL,
  `fed_nextcrawl` datetime DEFAULT NULL,
  `fed_datecreated` datetime NOT NULL,
  PRIMARY KEY (`fed_id`),
  KEY `fed_link` (`fed_link`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `folders` (
  `flr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `flr_title` varchar(255) NOT NULL,
  `flr_direction` char(3) DEFAULT NULL,
  `flr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`flr_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `flr_title` (`flr_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `followers` (
  `fws_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `fws_following` bigint(20) unsigned NOT NULL,
  `fws_datecreated` datetime NOT NULL,
  PRIMARY KEY (`fws_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `fws_following` (`fws_following`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `history` (
  `hst_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `itm_id` bigint(20) unsigned NOT NULL,
  `hst_real` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `hst_datecreated` datetime NOT NULL,
  PRIMARY KEY (`hst_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `itm_id` (`itm_id`),
  KEY `hst_real` (`hst_real`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `items` (
  `itm_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fed_id` bigint(20) unsigned NOT NULL,
  `auh_id` bigint(20) unsigned DEFAULT NULL,
  `itm_title` varchar(255) NOT NULL,
  `itm_link` varchar(255) NOT NULL,
  `itm_author` varchar(255) DEFAULT NULL,
  `itm_content` longtext,
  `itm_latitude` double DEFAULT NULL,
  `itm_longitude` double DEFAULT NULL,
  `itm_date` datetime NOT NULL,
  `itm_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `itm_datecreated` datetime NOT NULL,
  PRIMARY KEY (`itm_id`),
  KEY `fed_id` (`fed_id`),
  KEY `auh_id` (`auh_id`),
  KEY `itm_link` (`itm_link`),
  KEY `itm_date` (`itm_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `members` (
  `mbr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_email` varchar(255) NOT NULL,
  `mbr_password` char(40) NOT NULL,
  `mbr_nickname` varchar(255) DEFAULT NULL,
  `mbr_gravatar` varchar(255) DEFAULT NULL,
  `mbr_description` text,
  `mbr_administrator` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `token_password` char(40) DEFAULT NULL,
  `token_share` char(40) DEFAULT NULL,
  `token_msapplication` char(40) DEFAULT NULL,
  `mbr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`mbr_id`),
  UNIQUE KEY `mbr_email` (`mbr_email`),
  UNIQUE KEY `token_password` (`token_password`),
  UNIQUE KEY `mbr_nickname` (`mbr_nickname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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

INSERT INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('folders', 'boolean', '1', NULL, 1, 0, 0, NOW()),
('gravatar', 'boolean', '1', NULL, 1, 0, 0, NOW()),
('gravatar_default', 'string', 'identicon', 'identicon, mm, monsterid, retro, wavatar', 1, 1, 0, NOW()),
('gravatar_rating', 'string', 'pg', 'g, pg, r, x', 1, 1, 0, NOW()),
('gravatar_size', 'integer', '70', NULL, 1, 0, 0, NOW()),
('menu_geolocation_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('menu_audio_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('menu_video_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('readability_parser_key', 'string', NULL, NULL, 1, 1, 0, NOW()),
('sender_email', 'email', 'mailer@readerself.com', NULL, 1, 0, 0, NOW()),
('sender_name', 'string', 'Reader Self', NULL, 1, 0, 0, NOW()),
('shared_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('share_external_email', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('social_buttons', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('starred_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('tags', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('share_external', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('title', 'string', 'Reader Self', NULL, 1, 0, 0, NOW()),
('members_list', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('register_multi', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('refresh_by_cron', 'boolean', '1', NULL, 1, 0, 0, NOW()),
('menu_authors', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('elasticsearch/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('elasticsearch/index', 'string', 'readerself', NULL, 1, 0, 0, NOW()),
('elasticsearch/url', 'string', 'http://127.0.0.1:9200', NULL, 1, 0, 0, NOW()),
('facebook/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('facebook/id', 'string', NULL, NULL, 1, 0, 0, NOW()),
('facebook/secret', 'string', NULL, NULL, 1, 0, 0, NOW()),
('material-design/colors/text/card-title', 'varchar', 'white', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/card-actions', 'varchar', 'black', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/link', 'varchar', 'pink', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/content', 'varchar', 'black', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/layout', 'varchar', 'grey-100', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/header', 'varchar', 'teal', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/button', 'varchar', 'pink', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/card', 'varchar', 'white', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/menu', 'varchar', 'white', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/card-title', 'varchar', 'teal', NULL, 1, 0, 0, NOW());

CREATE TABLE IF NOT EXISTS `share` (
  `shr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `itm_id` bigint(20) unsigned NOT NULL,
  `shr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`shr_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `itm_id` (`itm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `sub_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `fed_id` bigint(20) unsigned NOT NULL,
  `flr_id` bigint(20) unsigned DEFAULT NULL,
  `sub_title` varchar(255) DEFAULT NULL,
  `sub_priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sub_direction` char(3) DEFAULT NULL,
  `sub_datecreated` datetime NOT NULL,
  PRIMARY KEY (`sub_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `fed_id` (`fed_id`),
  KEY `flr_id` (`flr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
