#2015-08-07
CREATE INDEX "categories_itm_id" ON "categories" ("itm_id");

CREATE UNIQUE INDEX "connections_token_connection" ON "connections" ("token_connection");
CREATE INDEX "connections_mbr_id" ON "connections" ("mbr_id");

CREATE INDEX "elasticsearch_items_itm_id" ON "elasticsearch_items" ("itm_id");

CREATE INDEX "enclosures_itm_id" ON "enclosures" ("itm_id");

CREATE INDEX "favorites_mbr_id" ON "favorites" ("mbr_id");
CREATE INDEX "favorites_itm_id" ON "favorites" ("itm_id");

CREATE INDEX "feeds_fed_link" ON "feeds" ("fed_link");

CREATE INDEX "folders_mbr_id" ON "folders" ("mbr_id");
CREATE INDEX "folders_flr_title" ON "folders" ("flr_title");

CREATE INDEX "followers_mbr_id" ON "followers" ("mbr_id");
CREATE INDEX "followers_fws_following" ON "followers" ("fws_following");

CREATE INDEX "history_mbr_id" ON "history" ("mbr_id");
CREATE INDEX "history_itm_id" ON "history" ("itm_id");
CREATE INDEX "history_hst_real" ON "history" ("hst_real");

CREATE INDEX "items_fed_id" ON "items" ("fed_id");
CREATE INDEX "items_itm_link" ON "items" ("itm_link");
CREATE INDEX "items_itm_date" ON "items" ("itm_date");

CREATE UNIQUE INDEX "members_token_password" ON "members" ("token_password");
CREATE UNIQUE INDEX "members_mbr_nickname" ON "members" ("mbr_nickname");
CREATE UNIQUE INDEX "members_mbr_email" ON "members" ("mbr_email");

CREATE UNIQUE INDEX "settings_stg_code" ON "settings" ("stg_code");

CREATE INDEX "share_mbr_id" ON "share" ("mbr_id");
CREATE INDEX "share_itm_id" ON "share" ("itm_id");

CREATE INDEX "subscriptions_mbr_id" ON "subscriptions" ("mbr_id");
CREATE INDEX "subscriptions_fed_id" ON "subscriptions" ("fed_id");
CREATE INDEX "subscriptions_flr_id" ON "subscriptions" ("flr_id");

#2015-08-08
INSERT INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('material-design/colors/text/link', 'varchar', 'pink', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/text/content', 'varchar', 'black', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/background/layout', 'varchar', 'grey-100', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/background/header', 'varchar', 'teal', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/background/button', 'varchar', 'pink', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/background/card', 'varchar', 'white', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/background/card-title-highlight', 'varchar', 'teal', NULL, 1, 0, 0, datetime('now'));

#2015-08-09
CREATE TABLE IF NOT EXISTS `authors` (
  `auh_id` integer PRIMARY KEY AUTOINCREMENT,
  `auh_title` varchar(255) NOT NULL,
  `auh_datecreated` datetime NOT NULL);
CREATE UNIQUE INDEX "authors_auh_title" ON "authors" ("auh_title");

BEGIN;
CREATE TABLE "adminer_items" (
  "itm_id" integer NULL PRIMARY KEY AUTOINCREMENT,
  "fed_id" integer NOT NULL,
  "auh_id" integer NULL,
  "itm_title" text NOT NULL,
  "itm_link" text NOT NULL,
  "itm_author" text NULL,
  "itm_content" text NULL,
  "itm_latitude" real NULL,
  "itm_longitude" real NULL,
  "itm_date" numeric NOT NULL,
  "itm_deleted" integer NOT NULL DEFAULT '0',
  "itm_datecreated" numeric NOT NULL
);
INSERT INTO "adminer_items" ("itm_id", "fed_id", "itm_title", "itm_link", "itm_author", "itm_content", "itm_latitude", "itm_longitude", "itm_date", "itm_deleted", "itm_datecreated") SELECT "itm_id", "fed_id", "itm_title", "itm_link", "itm_author", "itm_content", "itm_latitude", "itm_longitude", "itm_date", "itm_deleted", "itm_datecreated" FROM "items";
DROP TABLE "items";
ALTER TABLE "adminer_items" RENAME TO "items";
CREATE INDEX "items_fed_id" ON "items" ("fed_id");
CREATE INDEX "items_itm_link" ON "items" ("itm_link");
CREATE INDEX "items_itm_date" ON "items" ("itm_date");
COMMIT;

CREATE INDEX "items_auh_id" ON "items" ("auh_id");

INSERT INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('material-design/colors/meta/theme', 'varchar', '#009688', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/text/button', 'varchar', 'white', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/background/menu', 'varchar', 'white', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/text/card-title-highlight', 'varchar', 'white', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/text/card-title', 'varchar', 'black', NULL, 1, 0, 0, datetime('now')),
('material-design/colors/text/card-actions', 'varchar', 'black', NULL, 1, 0, 0, datetime('now'));

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` integer PRIMARY KEY AUTOINCREMENT,
  `tag_title` varchar(255) NOT NULL,
  `tag_datecreated` datetime NOT NULL);
CREATE UNIQUE INDEX "tags_tag_title" ON "tags" ("tag_title");

CREATE TABLE IF NOT EXISTS `tags_items` (
  `tag_itm_id` integer PRIMARY KEY AUTOINCREMENT,
  `tag_id` INTEGER NOT NULL,
  `itm_id` INTEGER NOT NULL,
  `tag_itm_datecreated` datetime NOT NULL);
CREATE INDEX "tags_items_tag_id" ON "tags_items" ("tag_id");
CREATE INDEX "tags_items_itm_id" ON "tags_items" ("itm_id");
