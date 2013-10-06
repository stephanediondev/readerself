### Install

Edit [/application/config/readerself_config.php](/application/config/readerself_config.php) to define "salt_password" (some letters and numbers to secure your password)

Edit [/application/config/database.php](/application/config/database.php) to define "username", "password" and "database" ("hostname" if necessary)

Load SQL commands below in your database

Launch in a browser to register an account

Add to cron (hourly) => cd /path-to-installation && php index.php refresh items

```sql
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
  `mbr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`mbr_id`),
  UNIQUE KEY `mbr_email` (`mbr_email`),
  UNIQUE KEY `token_password` (`token_password`),
  UNIQUE KEY `mbr_nickname` (`mbr_nickname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
```
