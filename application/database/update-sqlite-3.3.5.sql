REPLACE INTO `settings` (`stg_code`, `stg_type`, `stg_value`, `stg_note`, `stg_is_global`, `stg_is_member`, `stg_is_subscription`, `stg_datecreated`) VALUES
('evernote/enabled', 'boolean', '0', NULL, 1, 0, 0, datetime('now')),
('evernote/sandbox', 'boolean', '0', NULL, 1, 0, 0, datetime('now')),
('evernote/consumer_key', 'string', NULL, NULL, 1, 0, 0, datetime('now')),
('evernote/consumer_secret', 'string', NULL, NULL, 1, 0, 0, datetime('now'));

CREATE TABLE IF NOT EXISTS `tokens` (
  `tok_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `tok_type` varchar(255) NOT NULL,
  `tok_value` varchar(255) NOT NULL,
  `tok_sandbox` INTEGER NOT NULL DEFAULT '0',
  `tok_datecreated` datetime NOT NULL);
CREATE INDEX "tokens_mbr_id" ON "tokens" ("mbr_id");
CREATE INDEX "tokens_tok_type" ON "tokens" ("tok_type");
