<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['folders'] = TRUE;

$config['gravatar'] = TRUE;
$config['gravatar_default'] = 'identicon';//identicon, mm, monsterid, retro, wavatar
$config['gravatar_rating'] = 'pg';//g, pg, r, x
$config['gravatar_size'] = 100;

$config['ldap'] = FALSE;
$config['ldap_server'] = 'ldap://localhost';
$config['ldap_port'] = 389;
$config['ldap_protocol'] = 3;
$config['ldap_rootdn'] = 'cn=Manager,dc=my-domain,dc=com';
$config['ldap_rootpw'] = 'secret';
$config['ldap_basedn'] = 'dc=my-domain,dc=com';
$config['ldap_filter'] = 'mail=[email]';

$config['menu_geolocation_items'] = TRUE;
$config['menu_audio_items'] = TRUE;

$config['readability_parser_key'] = '';
$config['register_multi'] = FALSE;
$config['salt_password'] = '';

$config['sender_email'] = 'mailer@readerself.com';
$config['sender_name'] = 'Reader Self';

$config['share'] = TRUE;
$config['share_by_email'] = TRUE;
$config['social'] = TRUE;
$config['star'] = TRUE;
$config['tags'] = TRUE;
$config['title'] = 'Reader Self';
