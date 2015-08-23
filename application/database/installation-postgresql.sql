CREATE TABLE IF NOT EXISTS authors (
  auh_id serial,
  auh_title varchar(255) NOT NULL,
  auh_datecreated timestamp NOT NULL,
  CONSTRAINT authors_auh_title UNIQUE (auh_title)
);

CREATE TABLE IF NOT EXISTS categories (
  cat_id serial,
  itm_id int,
  cat_title varchar(255) NOT NULL,
  cat_datecreated timestamp NOT NULL
);
CREATE INDEX categories_itm_id ON categories USING btree (itm_id);

CREATE TABLE IF NOT EXISTS connections (
  cnt_id serial,
  mbr_id int,
  token_connection char(40) DEFAULT NULL,
  cnt_ip varchar(255) DEFAULT NULL,
  cnt_agent varchar(255) NOT NULL,
  cnt_datecreated timestamp NOT NULL,
  CONSTRAINT connections_token_connection UNIQUE (token_connection)
);
CREATE INDEX connections_mbr_id ON connections USING btree (mbr_id);

CREATE TABLE IF NOT EXISTS crawler (
  crr_id serial,
  crr_time double precision NOT NULL,
  crr_memory int,
  crr_feeds int,
  crr_errors int,
  crr_datecreated timestamp NOT NULL
);

CREATE TABLE IF NOT EXISTS enclosures (
  enr_id serial,
  itm_id int,
  enr_link varchar(255) NOT NULL,
  enr_type varchar(255) NOT NULL,
  enr_length int,
  enr_width int,
  enr_height int,
  enr_datecreated timestamp NOT NULL
);
CREATE INDEX enclosures_itm_id ON enclosures USING btree (itm_id);

CREATE TABLE IF NOT EXISTS favorites (
  fav_id serial,
  mbr_id int,
  itm_id int,
  fav_datecreated timestamp NOT NULL
);
CREATE INDEX favorites_mbr_id ON favorites USING btree (mbr_id);
CREATE INDEX favorites_itm_id ON favorites USING btree (itm_id);

CREATE TYPE fed_type AS ENUM ('rss', 'atom');

CREATE TABLE IF NOT EXISTS feeds (
  fed_id serial,
  fed_title varchar(255) DEFAULT NULL,
  fed_url varchar(255) DEFAULT NULL,
  fed_link varchar(255) NOT NULL,
  fed_host varchar(255) DEFAULT NULL,
  fed_type fed_type DEFAULT NULL,
  fed_image varchar(255) DEFAULT NULL,
  fed_description text,
  fed_direction char(3) DEFAULT NULL,
  fed_lasterror varchar(255) DEFAULT NULL,
  fed_lastcrawl timestamp DEFAULT NULL,
  fed_nextcrawl timestamp DEFAULT NULL,
  fed_datecreated timestamp NOT NULL
);
CREATE INDEX feeds_fed_link ON feeds USING btree (fed_link);

CREATE TABLE IF NOT EXISTS folders (
  flr_id serial,
  mbr_id int,
  flr_title varchar(255) NOT NULL,
  flr_direction char(3) DEFAULT NULL,
  flr_datecreated timestamp NOT NULL
);
CREATE INDEX folders_mbr_id ON folders USING btree (mbr_id);

CREATE TABLE IF NOT EXISTS followers (
  fws_id serial,
  mbr_id int,
  fws_following int,
  fws_datecreated timestamp NOT NULL
);

CREATE TABLE IF NOT EXISTS history (
  hst_id serial,
  mbr_id int,
  itm_id int,
  hst_real smallint,
  hst_datecreated timestamp NOT NULL
);
CREATE INDEX history_mbr_id ON history USING btree (mbr_id);
CREATE INDEX history_itm_id ON history USING btree (itm_id);

CREATE TABLE IF NOT EXISTS items (
  itm_id serial,
  fed_id int,
  auh_id int,
  itm_title varchar(255) NOT NULL,
  itm_link varchar(255) NOT NULL,
  itm_author varchar(255) DEFAULT NULL,
  itm_content text,
  itm_latitude double precision DEFAULT NULL,
  itm_longitude double precision DEFAULT NULL,
  itm_date timestamp NOT NULL,
  itm_deleted smallint,
  itm_datecreated timestamp NOT NULL
);
CREATE INDEX items_fed_id ON items USING btree (fed_id);
CREATE INDEX items_auh_id ON items USING btree (auh_id);
CREATE INDEX items_itm_link ON items USING btree (itm_link);
CREATE INDEX items_itm_date ON items USING btree (itm_date);

CREATE TABLE IF NOT EXISTS members (
  mbr_id serial,
  mbr_email varchar(255) NOT NULL,
  mbr_password char(40) NOT NULL,
  mbr_nickname varchar(255) DEFAULT NULL,
  mbr_gravatar varchar(255) DEFAULT NULL,
  mbr_description text,
  mbr_administrator smallint,
  token_password char(40) DEFAULT NULL,
  token_share char(40) DEFAULT NULL,
  token_msapplication char(40) DEFAULT NULL,
  mbr_datecreated timestamp NOT NULL,
  CONSTRAINT members_mbr_email UNIQUE (mbr_email),
  CONSTRAINT members_token_password UNIQUE (token_password),
  CONSTRAINT members_mbr_nickname UNIQUE (mbr_nickname)
);

CREATE TABLE IF NOT EXISTS settings (
  stg_id serial,
  stg_code varchar(255) NOT NULL,
  stg_type varchar(255) NOT NULL,
  stg_value varchar(255) DEFAULT NULL,
  stg_note varchar(255) DEFAULT NULL,
  stg_is_global smallint,
  stg_is_member smallint,
  stg_is_subscription smallint,
  stg_datecreated timestamp NOT NULL,
  CONSTRAINT settings_stg_code UNIQUE (stg_code)
);

INSERT INTO settings (stg_code, stg_type, stg_value, stg_note, stg_is_global, stg_is_member, stg_is_subscription, stg_datecreated) VALUES
('wallabag/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('wallabag/url', 'string', 'http://localhost/wallabag', 'URL to installation, without trailing slash', 1, 0, 0, NOW()),
('shaarli/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('shaarli/url', 'string', 'http://localhost/shaarli', 'URL to installation, without trailing slash', 1, 0, 0, NOW()),
('proxy/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('proxy/http_only', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('folders', 'boolean', '1', NULL, 1, 0, 0, NOW()),
('gravatar', 'boolean', '1', NULL, 1, 0, 0, NOW()),
('gravatar_default', 'string', 'identicon', 'identicon, mm, monsterid, retro, wavatar', 1, 1, 0, NOW()),
('gravatar_rating', 'string', 'pg', 'g, pg, r, x', 1, 1, 0, NOW()),
('gravatar_size', 'integer', '70', NULL, 1, 0, 0, NOW()),
('menu_geolocation_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('menu_audio_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('menu_video_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('readability_parser_key', 'string', NULL, NULL, 1, 1, 0, NOW()),
('sender_email', 'email', 'mailer@readerself.com', NULL, 1, 0, 0, NOW()),
('sender_name', 'string', 'Reader Self', NULL, 1, 0, 0, NOW()),
('shared_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('share_external_email', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('social_buttons', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('starred_items', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('tags', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('share_external', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('title', 'string', 'Reader Self', NULL, 1, 0, 0, NOW()),
('members_list', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('register_multi', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('refresh_by_cron', 'boolean', '1', NULL, 1, 0, 0, NOW()),
('menu_authors', 'boolean', '1', NULL, 1, 1, 0, NOW()),
('elasticsearch/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('elasticsearch/index', 'string', 'readerself', NULL, 1, 0, 0, NOW()),
('elasticsearch/url', 'string', 'http://127.0.0.1:9200', NULL, 1, 0, 0, NOW()),
('facebook/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('facebook/id', 'string', NULL, NULL, 1, 0, 0, NOW()),
('facebook/secret', 'string', NULL, NULL, 1, 0, 0, NOW()),
('material-design/colors/meta/theme', 'varchar', '#009688', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/card-title-highlight', 'varchar', 'white', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/card-title', 'varchar', 'black', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/card-actions', 'varchar', 'black', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/link', 'varchar', 'pink', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/content', 'varchar', 'black', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/layout', 'varchar', 'grey-100', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/header', 'varchar', 'teal', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/button', 'varchar', 'pink', NULL, 1, 0, 0, NOW()),
('material-design/colors/text/button', 'varchar', 'white', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/card', 'varchar', 'white', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/menu', 'varchar', 'white', NULL, 1, 0, 0, NOW()),
('material-design/colors/background/card-title-highlight', 'varchar', 'teal', NULL, 1, 0, 0, NOW()),
('evernote/enabled', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('evernote/sandbox', 'boolean', '0', NULL, 1, 0, 0, NOW()),
('evernote/consumer_key', 'string', NULL, NULL, 1, 0, 0, NOW()),
('evernote/consumer_secret', 'string', NULL, NULL, 1, 0, 0, NOW());

CREATE TABLE IF NOT EXISTS share (
  shr_id serial,
  mbr_id int,
  itm_id int,
  shr_datecreated timestamp NOT NULL
);

CREATE TABLE IF NOT EXISTS subscriptions (
  sub_id serial,
  mbr_id int,
  fed_id int,
  flr_id int DEFAULT NULL,
  sub_title varchar(255) DEFAULT NULL,
  sub_priority smallint,
  sub_direction char(3) DEFAULT NULL,
  sub_datecreated timestamp NOT NULL
);

CREATE TABLE IF NOT EXISTS tags (
  tag_id serial,
  tag_title varchar(255) NOT NULL,
  tag_datecreated timestamp NOT NULL,
  CONSTRAINT tags_tag_title UNIQUE (tag_title)
);

CREATE TABLE IF NOT EXISTS tags_items (
  tag_itm_id serial,
  tag_id int,
  itm_id int,
  tag_itm_datecreated timestamp NOT NULL
);
CREATE INDEX tags_items_tag_id ON tags_items USING btree (tag_id);
CREATE INDEX tags_items_itm_id ON tags_items USING btree (itm_id);

CREATE TABLE IF NOT EXISTS tokens (
  tok_id serial,
  mbr_id int,
  tok_type varchar(255) NOT NULL,
  tok_value varchar(255) NOT NULL,
  tok_sandbox smallint,
  tok_datecreated timestamp NOT NULL
);
CREATE INDEX tokens_mbr_id ON tokens USING btree (mbr_id);
CREATE INDEX tokens_tok_type ON tokens USING btree (tok_type);
