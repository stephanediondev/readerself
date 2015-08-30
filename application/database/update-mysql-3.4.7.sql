REPLACE INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('instagram/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('instagram/client_id', 'string', NULL, NULL, 1, 0, 0, NOW()),
('instagram/client_secret', 'string', NULL, NULL, 1, 0, 0, NOW());