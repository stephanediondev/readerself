REPLACE INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('wallabag/enabled', 'boolean', '0', NULL, 1, 0, 0, datetime('now')),
('wallabag/url', 'string', 'http://localhost/wallabag', 'URL to installation, without trailing slash', 1, 0, 0, datetime('now'));
