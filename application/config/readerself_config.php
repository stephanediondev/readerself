<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['ldap'] = FALSE;
$config['ldap_server'] = 'ldap://localhost';
$config['ldap_port'] = 389;
$config['ldap_protocol'] = 3;
$config['ldap_rootdn'] = 'cn=Manager,dc=my-domain,dc=com';
$config['ldap_rootpw'] = 'secret';
$config['ldap_basedn'] = 'dc=my-domain,dc=com';
$config['ldap_filter'] = 'mail=[email]';

$config['members_list'] = FALSE;

$config['register_multi'] = FALSE;
$config['salt_password'] = '';
