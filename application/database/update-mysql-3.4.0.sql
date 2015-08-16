REPLACE INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('proxy/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('proxy/http_only', 'boolean', '0', NULL, 1, 0, 0, NOW());
