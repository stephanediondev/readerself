REPLACE INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('evernote/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('evernote/sandbox', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('evernote/consumer_key', 'string', NULL, NULL, 1, 0, 0, NOW()),
('evernote/consumer_secret', 'string', NULL, NULL, 1, 0, 0, NOW());

CREATE TABLE IF NOT EXISTS `tokens` (
  `tok_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mbr_id` bigint(20) unsigned NOT NULL,
  `tok_type` varchar(255) NOT NULL,
  `tok_value` varchar(255) NOT NULL,
  `tok_sandbox` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tok_datecreated` datetime NOT NULL,
  PRIMARY KEY (`tok_id`),
  KEY `mbr_id` (`mbr_id`),
  KEY `tok_type` (`tok_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
