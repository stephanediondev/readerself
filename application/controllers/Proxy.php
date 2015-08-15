<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proxy extends CI_Controller {
	public function index() {
		$opts = array(
			'http' => array(
				'method' => 'GET',
				'user_agent'=> $_SERVER['HTTP_USER_AGENT']
			)
		);

		$context = stream_context_create($opts);

		$file = $this->input->get('file');
		$imginfo = getimagesize($file);
		header('Content-type: '.$imginfo['mime']);
		readfile($file, false, $context);
		exit(0);
	}
}
