CREATE TABLE IF NOT EXISTS `tokens` (
  `tok_id` integer PRIMARY KEY AUTOINCREMENT,
  `mbr_id` INTEGER NOT NULL,
  `tok_type` varchar(255) NOT NULL,
  `tok_value` varchar(255) NOT NULL,
  `tok_datecreated` datetime NOT NULL);
CREATE INDEX "tokens_mbr_id" ON "tokens" ("mbr_id");
CREATE INDEX "tokens_tok_type" ON "tokens" ("tok_type");