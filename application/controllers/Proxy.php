<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proxy extends CI_Controller {
	public function index() {
		$file = $this->input->get('file');
		//echo file_get_contents($file);
		$imginfo = getimagesize($file);
		header('Content-type: '.$imginfo['mime']);
		readfile($file);
		exit(0);
	}
}
