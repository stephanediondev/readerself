#### Update

##### 2013-09-22

```sql
ALTER TABLE `members` ADD `mbr_nickname` VARCHAR( 255 ) NULL AFTER `mbr_password`;
ALTER TABLE `members` ADD UNIQUE (`mbr_nickname`);
ALTER TABLE `members` ADD `mbr_description` TEXT NULL AFTER `mbr_nickname`;
ALTER TABLE `members` ADD `mbr_gravatar` VARCHAR( 255 ) NULL AFTER `mbr_nickname`;
```

##### 2013-09-19

```sql
ALTER TABLE `feeds` ADD `fed_type` ENUM( 'rss', 'atom' ) NULL AFTER `fed_link`;
```

##### 2013-09-17

```sql
ALTER TABLE `feeds` ADD `fed_direction` CHAR( 3 ) NULL AFTER `fed_description`;
ALTER TABLE `folders` ADD `flr_direction` CHAR( 3 ) NULL AFTER `flr_title`;
```

##### 2013-09-16

```sql
ALTER TABLE `subscriptions` ADD `sub_direction` CHAR( 3 ) NULL AFTER `sub_priority`;
```

##### 2013-09-15

```sql
ALTER TABLE `subscriptions` ADD `sub_priority` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `sub_title`;
```

##### 2013-09-12

```sql
ALTER TABLE `crawler` ADD `crr_memory` INT UNSIGNED NULL AFTER `crr_time`;
```

##### 2013-09-11

```sql
DELETE FROM `connections` WHERE `token_connection` IS NULL;
ALTER TABLE `enclosures` CHANGE `enr_length` `enr_length` INT( 10 ) UNSIGNED NULL;
ALTER TABLE `enclosures` ADD `enr_width` INT UNSIGNED NULL AFTER `enr_length`;
ALTER TABLE `enclosures` ADD `enr_height` INT UNSIGNED NULL AFTER `enr_width`;
```

##### 2013-09-08

```sql
ALTER TABLE `items` ADD `itm_latitude` DOUBLE NULL AFTER `itm_content` ,
ADD `itm_longitude` DOUBLE NULL AFTER `itm_latitude`;
```

##### 2013-09-01

```sql
CREATE TABLE IF NOT EXISTS `crawler` (
  `crr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crr_time` double unsigned NOT NULL,
  `crr_count` int(10) unsigned NOT NULL,
  `crr_datecreated` datetime NOT NULL,
  PRIMARY KEY (`crr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
```

##### 2013-08-30

```sql
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
```

##### 2013-08-29

```sql
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
```

##### 2013-08-25

```sql
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
```

##### 2013-08-24

```sql
ALTER TABLE `history` ADD `hst_real` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `itm_id`;
ALTER TABLE `history` ADD INDEX ( `hst_real` );
ALTER TABLE `subscriptions` ADD `sub_title` VARCHAR( 255 ) NULL AFTER `tag_id`;
```

