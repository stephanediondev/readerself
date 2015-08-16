REPLACE INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('shaarli/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('shaarli/url', 'string', 'http://localhost/shaarli', 'URL to installation, without trailing slash', 1, 0, 0, NOW());
