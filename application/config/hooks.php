<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
'class'    => 'reader_hook',
'function' => 'post_controller_constructor',
'filename' => 'reader_hook.php',
'filepath' => 'hooks',
'params'   => array()
);

$hook['post_controller'][] = array(
'class'    => 'reader_hook',
'function' => 'post_controller',
'filename' => 'reader_hook.php',
'filepath' => 'hooks',
'params'   => array()
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */