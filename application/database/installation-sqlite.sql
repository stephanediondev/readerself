CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` integer PRIMARY KEY AUTOINCREMENT,
  `itm_id` INTEGER NOT NULL,
  `cat_title` varchar(255) NOT NULL,
  `cat_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `connections` (
  `cnt_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `token_connection` char(40) DEFAULT NULL,
  `cnt_ip` varchar(255) DEFAULT NULL,
  `cnt_agent` varchar(255) NOT NULL,
  `cnt_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `crawler` (
  `crr_id` integer PRIMARY KEY AUTOINCREMENT,
  `crr_time` double NOT NULL,
  `crr_memory` INTEGER DEFAULT NULL,
  `crr_feeds` INTEGER NOT NULL,
  `crr_errors` INTEGER DEFAULT NULL,
  `crr_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `enclosures` (
  `enr_id` integer PRIMARY KEY AUTOINCREMENT,
  `itm_id` INTEGER NOT NULL,
  `enr_link` varchar(255) NOT NULL,
  `enr_type` varchar(255) NOT NULL,
  `enr_length` INTEGER DEFAULT NULL,
  `enr_width` INTEGER DEFAULT NULL,
  `enr_height` INTEGER DEFAULT NULL,
  `enr_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `favorites` (
  `fav_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `itm_id` INTEGER NOT NULL,
  `fav_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `feeds` (
  `fed_id` integer PRIMARY KEY AUTOINCREMENT,
  `fed_title` varchar(255) DEFAULT NULL,
  `fed_url` varchar(255) DEFAULT NULL,
  `fed_link` varchar(255) NOT NULL,
  `fed_host` varchar(255) DEFAULT NULL,
  `fed_type` varchar(4) DEFAULT NULL DEFAULT NULL,
  `fed_image` varchar(255) DEFAULT NULL,
  `fed_description` text,
  `fed_direction` char(3) DEFAULT NULL,
  `fed_lasterror` varchar(255) DEFAULT NULL,
  `fed_lastcrawl` datetime DEFAULT NULL,
  `fed_nextcrawl` datetime DEFAULT NULL,
  `fed_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `folders` (
  `flr_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `flr_title` varchar(255) NOT NULL,
  `flr_direction` char(3) DEFAULT NULL,
  `flr_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `followers` (
  `fws_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `fws_following` INTEGER NOT NULL,
  `fws_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `history` (
  `hst_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `itm_id` INTEGER NOT NULL,
  `hst_real` INTEGER NOT NULL DEFAULT '1',
  `hst_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `items` (
  `itm_id` integer PRIMARY KEY AUTOINCREMENT,
  `fed_id` INTEGER NOT NULL,
  `itm_title` varchar(255) NOT NULL,
  `itm_link` varchar(255) NOT NULL,
  `itm_author` varchar(255) DEFAULT NULL,
  `itm_content` text,
  `itm_latitude` double DEFAULT NULL,
  `itm_longitude` double DEFAULT NULL,
  `itm_date` datetime NOT NULL,
  `itm_deleted` INTEGER NOT NULL DEFAULT '0',
  `itm_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `members` (
  `mbr_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_email` varchar(255) NOT NULL,
  `mbr_password` char(40) NOT NULL,
  `mbr_nickname` varchar(255) DEFAULT NULL,
  `mbr_gravatar` varchar(255) DEFAULT NULL,
  `mbr_description` text,
  `mbr_administrator` INTEGER NOT NULL DEFAULT '0',
  `token_password` char(40) DEFAULT NULL,
  `token_share` char(40) DEFAULT NULL,
  `token_msapplication` char(40) DEFAULT NULL,
  `mbr_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `settings` (
  `stg_id` integer PRIMARY KEY AUTOINCREMENT,
  `stg_code` varchar(255) NOT NULL,
  `stg_type` varchar(255) NOT NULL,
  `stg_value` varchar(255) DEFAULT NULL,
  `stg_note` varchar(255) DEFAULT NULL,
  `stg_is_global` INTEGER NOT NULL DEFAULT '0',
  `stg_is_member` INTEGER NOT NULL DEFAULT '0',
  `stg_is_subscription` INTEGER NOT NULL DEFAULT '0',
  `stg_datecreated` datetime NOT NULL);

INSERT INTO "settings" VALUES(1,'folders','boolean','1',NULL,1,0,0,NOW());
INSERT INTO "settings" VALUES(2,'gravatar','boolean','1',NULL,1,0,0,NOW());
INSERT INTO "settings" VALUES(3,'gravatar_default','string','identicon','identicon, mm, monsterid, retro, wavatar',1,1,0,NOW());
INSERT INTO "settings" VALUES(4,'gravatar_rating','string','pg','g, pg, r, x',1,1,0,NOW());
INSERT INTO "settings" VALUES(5,'gravatar_size','integer','70',NULL,1,0,0,NOW());
INSERT INTO "settings" VALUES(6,'menu_geolocation_items','boolean','1',NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(7,'menu_audio_items','boolean','1',NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(8,'menu_video_items','boolean','1',NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(9,'readability_parser_key','string',NULL,NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(10,'sender_email','email','mailer@readerself.com',NULL,1,0,0,NOW());
INSERT INTO "settings" VALUES(11,'sender_name','string','Reader Self',NULL,1,0,0,NOW());
INSERT INTO "settings" VALUES(12,'shared_items','boolean','1',NULL,1,1,0,'2013-10-12 21:38:11');
INSERT INTO "settings" VALUES(13,'share_external_email','boolean','1',NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(14,'social_buttons','boolean','1',NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(15,'starred_items','boolean','1',NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(16,'tags','boolean','1',NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(17,'share_external','boolean','1',NULL,1,1,0,NOW());
INSERT INTO "settings" VALUES(18,'title','string','Reader Self',NULL,1,0,0,NOW());
INSERT INTO "settings" VALUES(19,'members_list','boolean','0',NULL,1,0,0,NOW());
INSERT INTO "settings" VALUES(20,'register_multi','boolean','0',NULL,1,0,0,NOW());
INSERT INTO "settings" VALUES(21,'refresh_by_cron','boolean','1',NULL,1,0,0,NOW());

CREATE TABLE IF NOT EXISTS `share` (
  `shr_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `itm_id` INTEGER NOT NULL,
  `shr_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `sub_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `fed_id` INTEGER NOT NULL,
  `flr_id` INTEGER DEFAULT NULL,
  `sub_title` varchar(255) DEFAULT NULL,
  `sub_priority` INTEGER NOT NULL DEFAULT '0',
  `sub_direction` char(3) DEFAULT NULL,
  `sub_datecreated` datetime NOT NULL);
