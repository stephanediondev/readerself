<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proxy extends CI_Controller {
	public function index() {
		if(!$this->axipi_session->userdata('mbr_id')) {
			redirect(base_url());
		}

		$opts = array(
			'http' => array(
				'method' => 'GET',
				'user_agent'=> $_SERVER['HTTP_USER_AGENT']
			)
		);

		$context = stream_context_create($opts);

		$file = $this->input->get('file');
		if($file != '' && (substr($file, 0, 7) == 'http://' || substr($file, 0, 8) == 'https://')) {
			$imginfo = getimagesize($file);
			if($imginfo) {
				header('Content-type: '.$imginfo['mime']);
			}
			readfile($file, false, $context);
		}
		exit(0);
	}
}
