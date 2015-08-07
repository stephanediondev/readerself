CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` integer PRIMARY KEY AUTOINCREMENT,
  `itm_id` INTEGER NOT NULL,
  `cat_title` varchar(255) NOT NULL,
  `cat_datecreated` datetime NOT NULL);
CREATE INDEX "categories_itm_id" ON "categories" ("itm_id");

CREATE TABLE IF NOT EXISTS `connections` (
  `cnt_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `token_connection` char(40) DEFAULT NULL,
  `cnt_ip` varchar(255) DEFAULT NULL,
  `cnt_agent` varchar(255) NOT NULL,
  `cnt_datecreated` datetime NOT NULL);
CREATE UNIQUE INDEX "connections_token_connection" ON "connections" ("token_connection");
CREATE INDEX "connections_mbr_id" ON "connections" ("mbr_id");

CREATE TABLE IF NOT EXISTS `crawler` (
  `crr_id` integer PRIMARY KEY AUTOINCREMENT,
  `crr_time` double NOT NULL,
  `crr_memory` INTEGER DEFAULT NULL,
  `crr_feeds` INTEGER NOT NULL,
  `crr_errors` INTEGER DEFAULT NULL,
  `crr_datecreated` datetime NOT NULL);

CREATE TABLE IF NOT EXISTS `elasticsearch_items` (
  `id` integer PRIMARY KEY AUTOINCREMENT,
  `itm_id` INTEGER NOT NULL,
  `datecreated` datetime NOT NULL);
CREATE INDEX "elasticsearch_items_itm_id" ON "elasticsearch_items" ("itm_id");

CREATE TABLE IF NOT EXISTS `enclosures` (
  `enr_id` integer PRIMARY KEY AUTOINCREMENT,
  `itm_id` INTEGER NOT NULL,
  `enr_link` varchar(255) NOT NULL,
  `enr_type` varchar(255) NOT NULL,
  `enr_length` INTEGER DEFAULT NULL,
  `enr_width` INTEGER DEFAULT NULL,
  `enr_height` INTEGER DEFAULT NULL,
  `enr_datecreated` datetime NOT NULL);
CREATE INDEX "enclosures_itm_id" ON "enclosures" ("itm_id");

CREATE TABLE IF NOT EXISTS `favorites` (
  `fav_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `itm_id` INTEGER NOT NULL,
  `fav_datecreated` datetime NOT NULL);
CREATE INDEX "favorites_mbr_id" ON "favorites" ("mbr_id");
CREATE INDEX "favorites_itm_id" ON "favorites" ("itm_id");

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
CREATE INDEX "feeds_fed_link" ON "feeds" ("fed_link");

CREATE TABLE IF NOT EXISTS `folders` (
  `flr_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `flr_title` varchar(255) NOT NULL,
  `flr_direction` char(3) DEFAULT NULL,
  `flr_datecreated` datetime NOT NULL);
CREATE INDEX "folders_mbr_id" ON "folders" ("mbr_id");
CREATE INDEX "folders_flr_title" ON "folders" ("flr_title");

CREATE TABLE IF NOT EXISTS `followers` (
  `fws_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `fws_following` INTEGER NOT NULL,
  `fws_datecreated` datetime NOT NULL);
CREATE INDEX "followers_mbr_id" ON "followers" ("mbr_id");
CREATE INDEX "followers_fws_following" ON "followers" ("fws_following");

CREATE TABLE IF NOT EXISTS `history` (
  `hst_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `itm_id` INTEGER NOT NULL,
  `hst_real` INTEGER NOT NULL DEFAULT '1',
  `hst_datecreated` datetime NOT NULL);
CREATE INDEX "history_mbr_id" ON "history" ("mbr_id");
CREATE INDEX "history_itm_id" ON "history" ("itm_id");
CREATE INDEX "history_hst_real" ON "history" ("hst_real");

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
CREATE INDEX "items_fed_id" ON "items" ("fed_id");
CREATE INDEX "items_itm_link" ON "items" ("itm_link");
CREATE INDEX "items_itm_date" ON "items" ("itm_date");

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
CREATE UNIQUE INDEX "members_token_password" ON "members" ("token_password");
CREATE UNIQUE INDEX "members_mbr_nickname" ON "members" ("mbr_nickname");
CREATE UNIQUE INDEX "members_mbr_email" ON "members" ("mbr_email");

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
CREATE UNIQUE INDEX "settings_stg_code" ON "settings" ("stg_code");

INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('folders',	'boolean',	'1',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('gravatar',	'boolean',	'1',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('gravatar_default',	'string',	'identicon',	'identicon, mm, monsterid, retro, wavatar',	1,	1,	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('gravatar_rating',	'string',	'pg',	'g, pg, r, x',	1,	1,	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('gravatar_size',	'integer',	'70',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('menu_geolocation_items',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('menu_audio_items',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('menu_video_items',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('readability_parser_key',	'string',	NULL,	NULL,	1,	1,	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('sender_email',	'email',	'mailer@readerself.com',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('sender_name',	'string',	'Reader Self',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('shared_items',	'boolean',	'1',	NULL,	1,	1,	'0',	'2013-10-12 21:38:11');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('share_external_email',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('social_buttons',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:46');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('starred_items',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:47');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('tags',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:47');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('share_external',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:47');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('title',	'string',	'Reader Self',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:47');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('members_list',	'boolean',	'0',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:47');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('register_multi',	'boolean',	'0',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:47');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('refresh_by_cron',	'boolean',	'1',	NULL,	1,	'0',	'0',	'2015-08-07 11:27:47');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('menu_authors',	'boolean',	'1',	NULL,	1,	1,	'0',	'2015-08-07 11:27:47');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('elasticsearch/enabled',	'boolean',	'0',	NULL,	1,	'0',	'0',	'2015-06-13 06:23:41');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('elasticsearch/index',	'string',	NULL,	NULL,	1,	'0',	'0',	'2015-06-13 06:23:44');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('elasticsearch/url',	'string',	NULL,	NULL,	1,	'0',	'0',	'2015-06-13 06:24:18');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('facebook/enabled',	'boolean',	'0',	NULL,	1,	'0',	'0',	'2015-07-12 06:23:41');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('facebook/id',	'string',	NULL,	NULL,	1,	'0',	'0',	'2015-07-12 06:23:43');
INSERT INTO "settings" ("stg_code", "stg_type", "stg_value", "stg_note", "stg_is_global", "stg_is_member", "stg_is_subscription", "stg_datecreated") VALUES ('facebook/secret',	'string',	NULL,	NULL,	1,	'0',	'0',	'2015-07-12 06:24:18');

CREATE TABLE IF NOT EXISTS `share` (
  `shr_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `itm_id` INTEGER NOT NULL,
  `shr_datecreated` datetime NOT NULL);
CREATE INDEX "share_mbr_id" ON "share" ("mbr_id");
CREATE INDEX "share_itm_id" ON "share" ("itm_id");

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `sub_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `fed_id` INTEGER NOT NULL,
  `flr_id` INTEGER DEFAULT NULL,
  `sub_title` varchar(255) DEFAULT NULL,
  `sub_priority` INTEGER NOT NULL DEFAULT '0',
  `sub_direction` char(3) DEFAULT NULL,
  `sub_datecreated` datetime NOT NULL);
CREATE INDEX "subscriptions_mbr_id" ON "subscriptions" ("mbr_id");
CREATE INDEX "subscriptions_fed_id" ON "subscriptions" ("fed_id");
CREATE INDEX "subscriptions_flr_id" ON "subscriptions" ("flr_id");
