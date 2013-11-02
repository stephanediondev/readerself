<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['ldap'] = FALSE;
$config['ldap_server'] = 'ldap://localhost';
$config['ldap_port'] = 389;
$config['ldap_protocol'] = 3;
$config['ldap_rootdn'] = 'cn=Manager,dc=my-domain,dc=com';
$config['ldap_rootpw'] = 'secret';
$config['ldap_basedn'] = 'dc=my-domain,dc=com';
$config['ldap_filter'] = 'mail=[email]';

$config['salt_password'] = '';

$config['email_protocol'] = 'mail';//mail or smtp
$config['smtp_host'] = '';
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['smtp_port'] = 25;
