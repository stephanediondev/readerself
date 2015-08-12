defined('BASEPATH') OR exit('No direct script access allowed');

$config['salt_password'] = '<?php echo $salt_password; ?>';
$config['ldap'] = '';
$config['ldap_server'] = 'ldap://localhost';
$config['ldap_port'] = 389;
$config['ldap_protocol'] = 3;
$config['ldap_rootdn'] = 'cn=Manager,dc=my-domain,dc=com';
$config['ldap_rootpw'] = 'secret';
$config['ldap_basedn'] = 'dc=my-domain,dc=com';
$config['ldap_filter'] = 'mail=[email]';
$config['email_protocol'] = 'mail';
$config['smtp_host'] = '';
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['smtp_port'] = 25;
