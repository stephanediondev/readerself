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
  `crr_count` int(10) unsigned NOT NULL,
  `crr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`crr_id`)
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
  `itm_title` varchar(255) NOT NULL,
  `itm_link` varchar(255) NOT NULL,
  `itm_author` varchar(255) DEFAULT NULL,
  `itm_content` text NOT NULL,
  `itm_latitude` double DEFAULT NULL,
  `itm_longitude` double DEFAULT NULL,
  `itm_date` datetime NOT NULL,
  `itm_datecreated` datetime NOT NULL,
  PRIMARY KEY (`itm_id`),
  KEY `fed_id` (`fed_id`),
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
(20, 'register_multi', 'boolean', '0', NULL, 1, 0, 0, '2013-10-12 22:21:22'),
(21, 'refresh_by_cron', 'boolean', '1', NULL, 1, 0, 0, '2013-10-13 20:48:44');

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

INSERT INTO `feeds` (`fed_id`, `fed_title`, `fed_url`, `fed_link`, `fed_type`, `fed_image`, `fed_description`, `fed_direction`, `fed_lasterror`, `fed_lastcrawl`, `fed_nextcrawl`, `fed_datecreated`) VALUES
(1, 'The Verge -  All Posts', 'http://www.theverge.com/', 'http://www.theverge.com/rss/index.xml', 'atom', 'http://cdn1.sbnation.com/community_logos/34086/verge-fv.png', NULL, NULL, NULL, NULL, NULL, NOW()),
(2, 'Slashdot', 'http://slashdot.org/', 'http://rss.slashdot.org/Slashdot/slashdot', 'rss', 'http://a.fsdn.com/sd/topics/topicslashdot.gif', 'News for nerds, stuff that matters', NULL, NULL, NULL, NULL, NOW()),
(3, 'The Next Web', 'http://thenextweb.com/', 'http://feeds2.feedburner.com/thenextweb', 'rss', NULL, 'International technology news, business &amp; culture', NULL, NULL, NULL, NULL, NOW()),
(4, 'Fubiz™', 'http://www.fubiz.net/', 'http://www.fubiz.net/en/feed/', 'rss', NULL, 'Daily dose of inspiration', NULL, NULL, NULL, NULL, NOW());
